<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\table\column;

use framework\table\TableItemModel;

class DefaultColumn extends AbstractTableColumn
{
	private bool $renderNewLines = true;

	public function setRenderNewLines(bool $renderNewLines): void
	{
		$this->renderNewLines = $renderNewLines;
	}

	protected function renderCellValue(TableItemModel $tableItemModel): string
	{
		return $tableItemModel->renderValue(name: $this->identifier, renderNewLines: $this->renderNewLines);
	}
}