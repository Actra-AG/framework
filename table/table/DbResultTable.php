<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\table\table;

use framework\core\HttpRequest;
use framework\db\DbQuery;
use framework\db\FrameworkDB;
use framework\table\column\AbstractTableColumn;
use framework\table\filter\TableFilter;
use framework\table\renderer\SortableTableHeadRenderer;
use framework\table\renderer\TablePaginationRenderer;
use framework\table\TableHelper;
use framework\table\TableItemCollection;
use framework\table\TableItemModel;

class DbResultTable extends SmartTable
{
    protected const string PARAM_SORT = 'sort';
    protected const string PARAM_RESET = 'reset';
    protected const string PARAM_PAGE = 'page';
    public const string PARAM_FIND = 'find';

    protected const string sessionDataType = 'table';
    protected const string filter = '[filter]';
    protected const string pagination = '[pagination]';
    private(set) ?int $totalAmount = null {
        get {
            if (!is_null(value: $this->totalAmount)) {
                return $this->totalAmount;
            }
            $this->fillBySelectQuery();
            if (
                (
                    $this->getCurrentPaginationPage() === 1
                    && $this->filledAmount < $this->itemsPerPage
                )
                || $this->limitToOnePage
            ) {
                return $this->totalAmount = $this->filledAmount;
            }

            return $this->totalAmount = $this->dbQuery->getTotalAmount(db: $this->db);
        }
    }
    private(set) array $additionalLinkParameters = [];
    private bool $filledDataBySelectQuery = false;
    private ?AbstractTableColumn $defaultSortColumn = null;
    private TablePaginationRenderer $tablePaginationRenderer;
    private ?int $filledAmount = null;

    public function __construct(
        string $identifier, // Can be the name of main table, but must be unique per site
        public readonly FrameworkDB $db,
        public readonly DbQuery $dbQuery,
        private readonly ?TableFilter $tableFilter = null,
        ?TablePaginationRenderer $tablePaginationRenderer = null,
        ?SortableTableHeadRenderer $sortableTableHeadRenderer = null,
        private readonly int $itemsPerPage = 25,
        // Max rows in table before pagination starts, if result is not limited to one page
        public bool $limitToOnePage = false
    ) {
        if (is_null(value: $sortableTableHeadRenderer)) {
            $sortableTableHeadRenderer = new SortableTableHeadRenderer();
        }
        parent::__construct(
            identifier: $identifier,
            tableHeadRenderer: $sortableTableHeadRenderer,
            tableItemCollection: new TableItemCollection()
        );
        $this->noDataHtml = DbResultTable::filter . $this->noDataHtml;
        $this->fullHtml = DbResultTable::filter . SmartTable::totalAmount . DbResultTable::pagination . '<div class="table-global-wrap">' . SmartTable::table . '</div>' . DbResultTable::pagination;
        $this->tablePaginationRenderer = is_null(value: $tablePaginationRenderer) ? new TablePaginationRenderer(
        ) : $tablePaginationRenderer;
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
            search: [
                DbResultTable::filter,
                DbResultTable::pagination,
            ],
            replace: [
                is_null($this->tableFilter) ? '' : $this->tableFilter->render(),
                $this->tablePaginationRenderer->render(dbResultTable: $this, entriesPerPage: $this->itemsPerPage),
            ],
            subject: $html
        );
    }

    public function fillBySelectQuery(): void
    {
        if ($this->filledDataBySelectQuery) {
            return;
        }

        if (!is_null(value: $this->tableFilter)) {
            $this->tableFilter->validate(dbResultTable: $this);
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
            $this->addDataItem(tableItemModel: new TableItemModel(dataObject: $dataItem));
        }
        $this->filledDataBySelectQuery = true;
        $this->filledAmount = count(value: $res);
    }

    private function initSorting(): void
    {
        $availableSortOptions = [];
        foreach ($this->columns as $abstractTableColumn) {
            if ($abstractTableColumn->isSortable) {
                $availableSortOptions[] = $abstractTableColumn->identifier;
            }
        }

        $requestedSorting = trim(string: (string)HttpRequest::getInputString(keyName: DbResultTable::PARAM_SORT));
        if ($requestedSorting !== '') {
            $requestedSortingArr = explode(separator: '|', string: $requestedSorting);
            if (count(value: $requestedSortingArr) === 3) {
                $requestedSortTable = $requestedSortingArr[0];
                $requestedSortColumn = $requestedSortingArr[1];
                $requestedSortDirection = $requestedSortingArr[2];

                if (
                    $requestedSortTable === $this->identifier
                    && in_array(needle: $requestedSortColumn, haystack: $availableSortOptions)
                    && array_key_exists(key: $requestedSortDirection, array: TableHelper::OPPOSITE_SORT_DIRECTION)
                ) {
                    DbResultTable::saveToSession(
                        dataType: DbResultTable::sessionDataType,
                        identifier: $this->identifier,
                        index: 'sort_column',
                        value: $requestedSortColumn
                    );
                    DbResultTable::saveToSession(
                        dataType: DbResultTable::sessionDataType,
                        identifier: $this->identifier,
                        index: 'sort_direction',
                        value: $requestedSortDirection
                    );
                }
            }
        }

        if (empty($this->getCurrentSortColumn()) || !is_null(
                value: HttpRequest::getInputString(
                    keyName: DbResultTable::PARAM_RESET
                )
            )) {
            $defaultSortColumn = $this->defaultSortColumn;
            if (is_null(value: $defaultSortColumn)) {
                DbResultTable::saveToSession(
                    dataType: DbResultTable::sessionDataType,
                    identifier: $this->identifier,
                    index: 'sort_column',
                    value: current(array: $this->columns)->identifier
                );
                DbResultTable::saveToSession(
                    dataType: DbResultTable::sessionDataType,
                    identifier: $this->identifier,
                    index: 'sort_direction',
                    value: TableHelper::SORT_ASC
                );
            } else {
                DbResultTable::saveToSession(
                    dataType: DbResultTable::sessionDataType,
                    identifier: $this->identifier,
                    index: 'sort_column',
                    value: $defaultSortColumn->identifier
                );
                DbResultTable::saveToSession(
                    dataType: DbResultTable::sessionDataType,
                    identifier: $this->identifier,
                    index: 'sort_direction',
                    value: $defaultSortColumn->sortAscendingByDefault ? TableHelper::SORT_ASC : TableHelper::SORT_DESC
                );
            }
        }
    }

    public static function saveToSession(string $dataType, string $identifier, string $index, string $value): void
    {
        $_SESSION[$dataType][$identifier][$index] = $value;
    }

    public function getCurrentSortColumn(): ?string
    {
        return DbResultTable::getFromSession(
            dataType: DbResultTable::sessionDataType,
            identifier: $this->identifier,
            index: 'sort_column'
        );
    }

    public static function getFromSession(string $dataType, string $identifier, string $index): ?string
    {
        if (!isset($_SESSION[$dataType][$identifier])) {
            $_SESSION[$dataType][$identifier] = [];
        }

        return array_key_exists(
            key: $index,
            array: $_SESSION[$dataType][$identifier]
        ) ? $_SESSION[$dataType][$identifier][$index] : null;
    }

    private function initPaginationPage(): void
    {
        $inputPageArr = explode(
            separator: '|',
            string: trim(
                string: (string)HttpRequest::getInputString(
                    keyName: DbResultTable::PARAM_PAGE
                )
            )
        );
        $inputPage = (int)$inputPageArr[0];
        $inputTable = trim(string: array_key_exists(key: 1, array: $inputPageArr) ? $inputPageArr[1] : '');
        if ($inputTable === $this->identifier && $inputPage > 0) {
            $this->setCurrentPaginationPage(page: $inputPage);
        }

        if (
            $this->getCurrentPaginationPage() < 1
            || !is_null(value: HttpRequest::getInputString(keyName: DbResultTable::PARAM_FIND))
            || !is_null(value: HttpRequest::getInputString(keyName: DbResultTable::PARAM_RESET))
        ) {
            $this->setCurrentPaginationPage(page: 1);
        }
    }

    public function setCurrentPaginationPage(int $page): void
    {
        DbResultTable::saveToSession(
            dataType: DbResultTable::sessionDataType,
            identifier: $this->identifier,
            index: 'pagination_page',
            value: $page
        );
    }

    public function getCurrentPaginationPage(): int
    {
        return (int)DbResultTable::getFromSession(
            dataType: DbResultTable::sessionDataType,
            identifier: $this->identifier,
            index: 'pagination_page'
        );
    }

    public function getCurrentSortDirection(): string
    {
        return DbResultTable::getFromSession(
            dataType: DbResultTable::sessionDataType,
            identifier: $this->identifier,
            index: 'sort_direction'
        );
    }

    public function addAdditionalLinkParameter(string $key, string $value): void
    {
        $this->additionalLinkParameters[urlencode(string: $key)] = urlencode(string: $value);
    }
}