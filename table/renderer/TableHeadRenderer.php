<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\table\renderer;

use framework\table\column\AbstractTableColumn;
use framework\table\table\SmartTable;

class TableHeadRenderer
{
    protected bool $addColumnScopeAttribute = true;

    public function render(SmartTable $smartTable): string
    {
        $columns = [];

        foreach ($smartTable->columns as $abstractTableColumn) {
            $columns[] = $this->renderColumnHead($abstractTableColumn);
        }

        return implode(separator: PHP_EOL, array: [
            '<tr>',
            implode(separator: PHP_EOL, array: $columns),
            '</tr>',
        ]);
    }

    protected function renderColumnHead(AbstractTableColumn $abstractTableColumn): string
    {
        $columnCssClasses = $abstractTableColumn->columnCssClasses;
        $attributesArr = ['th'];
        if ($this->addColumnScopeAttribute) {
            $attributesArr[] = 'scope="col"';
        }
        if (count(value: $columnCssClasses) > 0) {
            $attributesArr[] = 'class="' . implode(separator: ' ', array: $columnCssClasses) . '"';
        }

        return '<' . implode(separator: ' ', array: $attributesArr) . '>' . $abstractTableColumn->label . '</th>';
    }
}