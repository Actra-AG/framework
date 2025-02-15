<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\table\column;

use framework\common\StringUtils;
use framework\table\TableItemModel;

class FileSizeColumn extends AbstractTableColumn
{
    public int $decimals = 2;

    protected function renderCellValue(TableItemModel $tableItemModel): string
    {
        $bytes = $tableItemModel->getRawValue(name: $this->identifier);
        if (is_null(value: $bytes)) {
            return '';
        }

        return StringUtils::formatBytes(bytes: $bytes, precision: $this->decimals);
    }
}