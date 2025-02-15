<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\table\column;

use framework\table\TableItemModel;

class BooleanColumn extends AbstractTableColumn
{
    public string $trueLabel = 'Ja';
    public string $falseLabel = 'Nein';

    protected function renderCellValue(TableItemModel $tableItemModel): string
    {
        $value = $tableItemModel->getRawValue(name: $this->identifier);

        if (is_null(value: $value)) {
            return '';
        }

        if ($value === 1 || $value === true) {
            return $this->trueLabel;
        }

        if ($value === 0 || $value === false) {
            return $this->falseLabel;
        }

        return $tableItemModel->renderValue(name: $this->identifier);
    }
}