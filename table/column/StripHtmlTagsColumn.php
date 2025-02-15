<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\table\column;

use framework\html\HtmlEncoder;
use framework\table\TableItemModel;

class StripHtmlTagsColumn extends AbstractTableColumn
{
    protected function renderCellValue(TableItemModel $tableItemModel): string
    {
        $strippedTags = strip_tags(string: $tableItemModel->getRawValue(name: $this->identifier));

        return HtmlEncoder::encodeKeepQuotes(value: $strippedTags);
    }
}