<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace framework\table\table;

use framework\core\HttpRequest;
use framework\datacheck\Sanitizer;
use framework\db\DbQuery;
use framework\db\FrameworkDB;
use framework\table\column\AbstractTableColumn;
use framework\table\filter\AbstractTableFilter;
use framework\table\renderer\SortableTableHeadRenderer;
use framework\table\renderer\TablePaginationRenderer;
use framework\table\TableHelper;
use framework\table\TableItemModel;

class DbResultTable extends SmartTable
{
	protected const PARAM_SORT = 'sort';
	protected const PARAM_RESET = 'reset';
	protected const PARAM_PAGE = 'page';
	protected const PARAM_FIND = 'find';

	protected const sessionDataType = 'table';
	protected const filter = '[filter]';
	protected const pagination = '[pagination]';

	private bool $filledDataBySelectQuery = false;
	private ?AbstractTableColumn $defaultSortColumn = null;
	private TablePaginationRenderer $tablePaginationRenderer;
	private ?int $filledAmount = null;
	private ?int $totalAmount = null;
	private array $additionalLinkParameters = [];

	public function __construct(
		string                     $identifier, // Can be the name of main table, but must be unique per site
		private                    readonly FrameworkDB          $db,
		private                    readonly DbQuery              $dbQuery,
		private                    readonly ?AbstractTableFilter $abstractTableFilter = null,
		?TablePaginationRenderer   $tablePaginationRenderer = null,
		?SortableTableHeadRenderer $sortableTableHeadRenderer = null,
		private                    readonly int                  $itemsPerPage = 25, // Max rows in table before pagination starts, if result is not limited to one page
		private bool               $limitToOnePage = false // Set to true to disable pagination
	)
	{
		if (is_null(value: $sortableTableHeadRenderer)) {
			$sortableTableHeadRenderer = new SortableTableHeadRenderer();
		}
		parent::__construct(identifier: $identifier, tableHeadRenderer: $sortableTableHeadRenderer);

		$this->setNoDataHtml(noDataHtml: DbResultTable::filter . $this->getNoDataHtml());
		$this->setFullHtml(fullHtml: DbResultTable::filter . SmartTable::totalAmount . DbResultTable::pagination . '<div class="table-global-wrap">' . SmartTable::table . '</div>' . DbResultTable::pagination);
		$this->tablePaginationRenderer = is_null(value: $tablePaginationRenderer) ? new TablePaginationRenderer() : $tablePaginationRenderer;
	}

	public static function getFromSession(string $dataType, string $identifier, string $index): ?string
	{
		if (!isset($_SESSION[$dataType][$identifier])) {
			$_SESSION[$dataType][$identifier] = [];
		}

		return $_SESSION[$dataType][$identifier][$index] ?? null;
	}

	public static function saveToSession(string $dataType, string $identifier, string $index, string $value): void
	{
		$_SESSION[$dataType][$identifier][$index] = $value;
	}

	public function getCurrentSortColumn(): ?string
	{
		return DbResultTable::getFromSession(dataType: DbResultTable::sessionDataType, identifier: $this->getIdentifier(), index: 'sort_column');
	}

	public function getCurrentSortDirection(): string
	{
		return DbResultTable::getFromSession(dataType: DbResultTable::sessionDataType, identifier: $this->getIdentifier(), index: 'sort_direction');
	}

	public function getCurrentPaginationPage(): int
	{
		return (int)DbResultTable::getFromSession(dataType: DbResultTable::sessionDataType, identifier: $this->getIdentifier(), index: 'pagination_page');
	}

	public function addColumn(AbstractTableColumn $abstractTableColumn, bool $isDefaultSortColumn = false): void
	{
		parent::addColumn(abstractTableColumn: $abstractTableColumn);

		if ($isDefaultSortColumn) {
			$this->defaultSortColumn = $abstractTableColumn;
		}
	}

	public function render(): string
	{
		$this->fillBySelectQuery();

		$html = parent::render();

		return str_replace(
			[
				DbResultTable::filter,
				DbResultTable::pagination,
			],
			[
				is_null($this->abstractTableFilter) ? '' : $this->abstractTableFilter->render(),
				$this->tablePaginationRenderer->render(dbResultTable: $this, entriesPerPage: $this->itemsPerPage),
			],
			$html
		);
	}

	public function fillBySelectQuery(): void
	{
		if ($this->filledDataBySelectQuery) {
			return;
		}

		if (!is_null($this->abstractTableFilter)) {
			$this->abstractTableFilter->validate(dbResultTable: $this);
		}
		$this->initSorting();
		$this->initPaginationPage();

		$sortColumn = $this->getCurrentSortColumn();
		$sortDirection = $this->getCurrentSortDirection();
		if ((string)$sortColumn !== '') {
			$this->dbQuery->addOrderPart(column: $sortColumn, ascending: ($sortDirection !== TableHelper::SORT_DESC));
		}
		$res = $this->dbQuery->selectFromDb(
			db: $this->db,
			offset: ($this->getCurrentPaginationPage() - 1) * $this->itemsPerPage,
			rowCount: $this->itemsPerPage
		);
		foreach ($res as $dataItem) {
			$this->addDataItem(tableItemModel: new TableItemModel($dataItem));
		}
		$this->filledDataBySelectQuery = true;
		$this->filledAmount = count($res);
	}

	private function initSorting(): void
	{
		$availableSortOptions = [];
		foreach ($this->getColumns() as $abstractTableColumn) {
			if ($abstractTableColumn->isSortable()) {
				$availableSortOptions[] = $abstractTableColumn->getIdentifier();
			}
		}

		$requestedSorting = Sanitizer::trimmedString(HttpRequest::getInputString(DbResultTable::PARAM_SORT));
		if ($requestedSorting !== '') {
			$requestedSortingArr = explode(separator: '|', string: $requestedSorting);
			if (count(value: $requestedSortingArr) === 3) {
				$requestedSortTable = $requestedSortingArr[0];
				$requestedSortColumn = $requestedSortingArr[1];
				$requestedSortDirection = $requestedSortingArr[2];

				if (
					$requestedSortTable === $this->getIdentifier()
					&& in_array(needle: $requestedSortColumn, haystack: $availableSortOptions)
					&& array_key_exists(key: $requestedSortDirection, array: TableHelper::OPPOSITE_SORT_DIRECTION)
				) {
					DbResultTable::saveToSession(dataType: DbResultTable::sessionDataType, identifier: $this->getIdentifier(), index: 'sort_column', value: $requestedSortColumn);
					DbResultTable::saveToSession(dataType: DbResultTable::sessionDataType, identifier: $this->getIdentifier(), index: 'sort_direction', value: $requestedSortDirection);
				}
			}
		}

		if (empty($this->getCurrentSortColumn()) || !is_null(HttpRequest::getInputString(keyName: DbResultTable::PARAM_RESET))) {
			$defaultSortColumn = $this->defaultSortColumn;
			if (is_null($defaultSortColumn)) {
				DbResultTable::saveToSession(dataType: DbResultTable::sessionDataType, identifier: $this->getIdentifier(), index: 'sort_column', value: current(array: $this->getColumns())->getIdentifier());
				DbResultTable::saveToSession(dataType: DbResultTable::sessionDataType, identifier: $this->getIdentifier(), index: 'sort_direction', value: TableHelper::SORT_ASC);
			} else {
				DbResultTable::saveToSession(DbResultTable::sessionDataType, $this->getIdentifier(), 'sort_column', $defaultSortColumn->getIdentifier());
				DbResultTable::saveToSession(DbResultTable::sessionDataType, $this->getIdentifier(), 'sort_direction', $defaultSortColumn->isSortAscendingByDefault() ? TableHelper::SORT_ASC : TableHelper::SORT_DESC);
			}
		}
	}

	private function initPaginationPage(): void
	{
		$inputPageArr = explode(separator: '|', string: Sanitizer::trimmedString(HttpRequest::getInputString(keyName: DbResultTable::PARAM_PAGE)));
		$inputPage = (int)$inputPageArr[0];
		$inputTable = Sanitizer::trimmedString($inputPageArr[1] ?? '');
		if ($inputTable === $this->getIdentifier() && $inputPage > 0) {
			DbResultTable::saveToSession(dataType: DbResultTable::sessionDataType, identifier: $this->getIdentifier(), index: 'pagination_page', value: $inputPage);
		}

		if (
			$this->getCurrentPaginationPage() < 1
			|| !is_null(HttpRequest::getInputString(keyName: DbResultTable::PARAM_FIND))
			|| !is_null(HttpRequest::getInputString(keyName: DbResultTable::PARAM_RESET))
		) {
			DbResultTable::saveToSession(dataType: DbResultTable::sessionDataType, identifier: $this->getIdentifier(), index: 'pagination_page', value: 1);
		}
	}

	public function getTotalAmount(): int
	{
		if (!is_null(value: $this->totalAmount)) {
			return $this->totalAmount;
		}

		$this->fillBySelectQuery();

		if (($this->getCurrentPaginationPage() === 1 && $this->filledAmount < $this->itemsPerPage) || $this->limitToOnePage) {
			return $this->totalAmount = $this->filledAmount;
		}

		return $this->totalAmount = $this->dbQuery->getTotalAmount(db: $this->db);
	}

	protected function getDb(): FrameworkDB
	{
		return $this->db;
	}

	public function getDbQuery(): DbQuery
	{
		return $this->dbQuery;
	}

	public function addAdditionalLinkParameter(string $key, string $value): void
	{
		$this->additionalLinkParameters[urlencode($key)] = urlencode(string: $value);
	}

	public function getAdditionalLinkParameters(): array
	{
		return $this->additionalLinkParameters;
	}

	public function setLimitToOnePage(bool $limitToOnePage): void
	{
		$this->limitToOnePage = $limitToOnePage;
	}
}