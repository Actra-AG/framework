<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\table\column;

use framework\table\TableItemModel;

class DefaultColumn extends AbstractTableColumn
{
    public bool $renderNewLines = true;

    protected function renderCellValue(TableItemModel $tableItemModel): string
    {
        return $tableItemModel->renderValue(name: $this->identifier, renderNewLines: $this->renderNewLines);
    }
}