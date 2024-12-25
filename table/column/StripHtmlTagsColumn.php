<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
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