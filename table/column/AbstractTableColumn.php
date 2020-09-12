<?php
/**
 * @author    Christof Moser <christof.moser@actra.ch>
 * @copyright Copyright (c) 2020, Actra AG
 */

namespace framework\table\column;

use framework\table\TableItemModel;

abstract class AbstractTableColumn
{
	private string $identifier;
	private string $label;
	private bool $isSortable;
	private bool $sortAscendingByDefault;
	private bool $columnScope;
	private array $columnCssClasses = [];
	private array $cellCssClasses = [];
	private ?string $tableIdentifier = null;

	public function __construct(string $identifier, string $label, bool $isSortable = false, bool $sortAscendingByDefault = true, bool $columnScope = true)
	{
		$this->identifier = $identifier;
		$this->label = $label;
		$this->isSortable = $isSortable;
		$this->sortAscendingByDefault = $sortAscendingByDefault;
		$this->columnScope = $columnScope;

		if ($this->isSortable) {
			$this->addColumnCssClass('sort');
		}
	}

	public function getIdentifier(): string
	{
		return $this->identifier;
	}

	public function isSortAscendingByDefault(): bool
	{
		return $this->sortAscendingByDefault;
	}

	public function setTableIdentifier(string $tableIdentifier): void
	{
		$this->tableIdentifier = $tableIdentifier;
	}

	public function addColumnCssClass(string $className): void
	{
		if (in_array($className, $this->columnCssClasses)) {
			return;
		}
		$this->columnCssClasses[] = $className;
	}

	public function addCellCssClass(string $className): void
	{
		if (in_array($className, $this->cellCssClasses)) {
			return;
		}
		$this->cellCssClasses[] = $className;
	}

	public function renderHead(string $sortLinkDirection): string
	{
		$attributesArr = ['th'];
		if ($this->columnScope) {
			$attributesArr[] = 'scope="col"';
		}
		if (!empty($this->columnCssClasses)) {
			$attributesArr[] = 'class="' . implode(' ', $this->columnCssClasses) . '"';
		}

		$htmlArr = ['<' . implode(' ', $attributesArr) . '>'];

		if ($this->isSortable) {
			$htmlArr[] = implode('', [
				'<a href="?sort=' . implode('|', [
					$this->tableIdentifier,
					$this->identifier,
					$sortLinkDirection,
				]) . '">',
				$this->label,
				'</a>',
			]);
		} else {
			$htmlArr[] = $this->label;
		}

		$htmlArr[] = '</th>';

		return implode('', $htmlArr);
	}

	public function renderCell(TableItemModel $tableItemModel): string
	{
		$attributesArr = ['td'];
		if (!empty($this->cellCssClasses)) {
			$attributesArr[] = 'class="' . implode(' ', $this->cellCssClasses) . '"';
		}

		return implode('', [
			'<' . implode(' ', $attributesArr) . '>',
			$this->renderCellValue($tableItemModel),
			'</td>',
		]);
	}

	abstract protected function renderCellValue(TableItemModel $tableItemModel): string;

	public function isSortable(): bool
	{
		return $this->isSortable;
	}
}
/* EOF */