<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\table\filter;

use framework\db\DbQueryData;

readonly class FilterOption
{
	public function __construct(
		public string      $identifier,
		public string      $label,
		public DbQueryData $whereCondition
	) {
	}

	public function render(string $selectedValue): string
	{
		$attributes = [
			'option',
			'value="' . $this->identifier . '"',
		];
		if ($this->identifier === $selectedValue) {
			$attributes[] = 'selected';
		}

		return '<' . implode(separator: ' ', array: $attributes) . '>' . $this->label . '</option>';
	}
}