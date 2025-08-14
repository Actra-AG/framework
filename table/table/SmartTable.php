<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\table\table;

use framework\table\column\AbstractTableColumn;
use framework\table\renderer\TableHeadRenderer;
use framework\table\TableItemCollection;
use framework\table\TableItemModel;
use LogicException;

// Can be extended or used directly to render a table with data from different sources
class SmartTable
{
    public const string totalAmount = '[totalAmount]';
    public const string table = '[table]';
    public const string tableHeader = '[tableHeader]';
    public const string tableBody = '[tableBody]';
    public const string cells = '[cells]';

    public const string totalAmountMessagePlaceholder = '[TOTAL_AMOUNT_MESSAGE]';
    public const string amount = '[AMOUNT]';
    /** @var SmartTable[] */
    private static array $instances = [];
    public string $noDataHtml = '<p class="no-entry">Es wurden keine Einträge gefunden.</p>';
    public string $totalAmountHtml = '<p class="search-result">' . SmartTable::totalAmountMessagePlaceholder . '</p>';
    public string $fullHtml = '<div class="table-meta table-meta-header">' . SmartTable::totalAmount . '</div><div class="table-global-wrap">' . SmartTable::table . '</div>';
    public string $tableHtml = '<thead>' . SmartTable::tableHeader . '</thead><tbody>' . SmartTable::tableBody . '</tbody>';
    public string $oddRowHtml = '<tr>' . SmartTable::cells . '</tr>';
    public string $evenRowHtml = '<tr>' . SmartTable::cells . '</tr>';
    public string $totalAmountMessage_oneResult = 'Es wurde <strong>1</strong> Resultat gefunden.';
    public string $totalAmountMessage_numResults = 'Es wurden <strong>' . SmartTable::amount . '</strong> Resultate gefunden.';
    /** @var AbstractTableColumn[] */
    private(set) array $columns = [];
    private array $cssClasses = [];

    public function __construct(
        public readonly string $identifier,
        private readonly TableHeadRenderer $tableHeadRenderer,
        public readonly TableItemCollection $tableItemCollection,
    ) {
        if (array_key_exists(key: $identifier, array: SmartTable::$instances)) {
            throw new LogicException(message: 'There is already a table with the same identifier ' . $identifier);
        }
        SmartTable::$instances[$this->identifier] = $this;
    }

    public function addCssClass(string $className): void
    {
        $this->cssClasses[] = $className;
    }

    public function addColumn(AbstractTableColumn $abstractTableColumn): void
    {
        $columnIdentifier = $abstractTableColumn->identifier;
        if (array_key_exists(key: $columnIdentifier, array: $this->columns)) {
            throw new LogicException(
                message: 'There is already a column with the same identifier ' . $columnIdentifier
            );
        }

        $abstractTableColumn->setTableIdentifier(tableIdentifier: $this->identifier);
        $this->columns[$columnIdentifier] = $abstractTableColumn;
    }

    public function addDataItem(TableItemModel $tableItemModel): void
    {
        $this->tableItemCollection->add(tableItemModel: $tableItemModel);
    }

    public function render(): string
    {
        $totalAmountOfItems = $this->getTotalAmount();
        if ($totalAmountOfItems === 1) {
            $totalAmountMessage = $this->totalAmountMessage_oneResult;
        } else {
            $totalAmountMessage = str_replace(
                search: SmartTable::amount,
                replace: number_format(num: $totalAmountOfItems, thousands_separator: '\''),
                subject: $this->totalAmountMessage_numResults
            );
        }
        $bodyArr = [];
        $rowNumber = 0;
        foreach ($this->tableItemCollection->list() as $tableItemModel) {
            $rowNumber++;
            $cells = [];
            foreach ($this->columns as $abstractTableColumn) {
                $cells[] = $abstractTableColumn->renderCell(tableItemModel: $tableItemModel);
            }
            $rowHtml = (($rowNumber % 2) === 0) ? $this->evenRowHtml : $this->oddRowHtml;
            $bodyArr[] = str_replace(
                search: SmartTable::cells,
                replace: implode(separator: PHP_EOL, array: $cells),
                subject: $rowHtml
            );
        }
        $tableAttributes = ['table'];
        if (count(value: $this->cssClasses) > 0) {
            $tableAttributes[] = 'class="' . implode(separator: ' ', array: $this->cssClasses) . '"';
        }
        $tableHtml = str_replace(
            search: [
                SmartTable::tableHeader,
                SmartTable::tableBody,
            ],
            replace: [
                $this->tableHeadRenderer->render(smartTable: $this),
                implode(separator: PHP_EOL, array: $bodyArr),
            ],
            subject: $this->tableHtml
        );

        $placeholders = [
            SmartTable::totalAmount => str_replace(
                search: SmartTable::totalAmountMessagePlaceholder,
                replace: $totalAmountMessage,
                subject: $this->totalAmountHtml
            ),
            SmartTable::table => implode(
                separator: PHP_EOL,
                array: [
                    '<' . implode(
                        separator: ' ',
                        array: $tableAttributes
                    ) . '>',
                    $tableHtml,
                    '</table>',
                ]
            ),
        ];

        $srcArr = array_keys(array: $placeholders);
        $rplArr = array_values(array: $placeholders);

        return ($totalAmountOfItems === 0) ? str_replace(
            search: $srcArr,
            replace: $rplArr,
            subject: $this->noDataHtml
        ) : str_replace(
            search: $srcArr,
            replace: $rplArr,
            subject: $this->fullHtml
        );
    }

    public function getTotalAmount(): int
    {
        return $this->tableItemCollection->count();
    }
}