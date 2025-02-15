<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\table\renderer;

use framework\table\column\AbstractTableColumn;
use framework\table\table\DbResultTable;
use framework\table\table\SmartTable;
use framework\table\TableHelper;
use LogicException;

class SortableTableHeadRenderer extends TableHeadRenderer
{
    public string $sortableColumnClass = 'sort';
    public string $sortableColumnClassActiveAsc = 'sort sort-asc';
    public string $sortableColumnClassActiveDesc = 'sort sort-desc';
    public string $sortLinkClassActiveAsc = '';
    public string $sortLinkClassActiveDesc = '';
    public string $sortableColumnLabelAddition = '';
    public string $sortableColumnLabelAdditionActiveAsc = '';
    public string $sortableColumnLabelAdditionActiveDesc = '';
    private DbResultTable $dbResultTable;

    public function render(SmartTable $smartTable): string
    {
        if (!($smartTable instanceof DbResultTable)) {
            throw new LogicException(message: '$smartTable must be an instance of DbResultTable');
        }

        $this->dbResultTable = $smartTable;

        return parent::render(smartTable: $smartTable);
    }

    protected function renderColumnHead(AbstractTableColumn $abstractTableColumn): string
    {
        $columnLabel = $abstractTableColumn->label;
        $columnCssClasses = $abstractTableColumn->columnCssClasses;

        if (!$abstractTableColumn->isSortable) {
            $labelHtml = $columnLabel;
        } else {
            $dbResultTable = $this->dbResultTable;
            $isActiveSortColumn = ($dbResultTable->getCurrentSortColumn() === $abstractTableColumn->identifier);
            if ($isActiveSortColumn) {
                $columnSortDirection = TableHelper::OPPOSITE_SORT_DIRECTION[$dbResultTable->getCurrentSortDirection()];
            } else {
                $columnSortDirection = $abstractTableColumn->sortAscendingByDefault ? TableHelper::SORT_ASC : TableHelper::SORT_DESC;
            }
            $getAttributes = [];
            foreach (
                array_merge([
                    'sort' => implode(separator: '|', array: [
                        $dbResultTable->identifier,
                        $abstractTableColumn->identifier,
                        $columnSortDirection,
                    ]),
                ], $dbResultTable->additionalLinkParameters) as $key => $val
            ) {
                $getAttributes[] = $key . '=' . $val;
            }
            $sortLinkAttributes = [
                'a',
                'href="?' . implode(separator: '&', array: $getAttributes) . '"',
            ];
            if ($isActiveSortColumn) {
                $lowerCaseSortDirection = strtolower(
                    string: TableHelper::OPPOSITE_SORT_DIRECTION[$columnSortDirection]
                );

                if (($lowerCaseSortDirection === 'asc') && $this->sortLinkClassActiveAsc !== '') {
                    $sortLinkAttributes[] = 'class="' . $this->sortLinkClassActiveAsc . '"';
                }
                if (($lowerCaseSortDirection === 'desc') && $this->sortLinkClassActiveDesc !== '') {
                    $sortLinkAttributes[] = 'class="' . $this->sortLinkClassActiveDesc . '"';
                }

                $columnCssClasses[] = ($lowerCaseSortDirection === 'asc') ? $this->sortableColumnClassActiveAsc : $this->sortableColumnClassActiveDesc;
                $labelAddition = ($lowerCaseSortDirection === 'asc') ? $this->sortableColumnLabelAdditionActiveAsc : $this->sortableColumnLabelAdditionActiveDesc;
            } else {
                $columnCssClasses[] = $this->sortableColumnClass;
                $labelAddition = $this->sortableColumnLabelAddition;
            }

            $labelHtml = '<' . implode(
                    separator: ' ',
                    array: $sortLinkAttributes
                ) . '>' . $columnLabel . $labelAddition . '</a>';
        }

        $attributesArr = ['th'];
        if ($this->addColumnScopeAttribute) {
            $attributesArr[] = 'scope="col"';
        }
        if (count(value: $columnCssClasses) > 0) {
            $attributesArr[] = 'class="' . implode(separator: ' ', array: $columnCssClasses) . '"';
        }

        return '<' . implode(separator: ' ', array: $attributesArr) . '>' . $labelHtml . '</th>';
    }
}