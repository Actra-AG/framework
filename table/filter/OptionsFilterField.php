<?php
/**
 * @author    Christof Moser <christof.moser@actra.ch>
 * @copyright Copyright (c) 2021, Actra AG
 */

namespace framework\table\filter;

use LogicException;
use framework\core\HttpRequest;
use framework\datacheck\Sanitizer;

class OptionsFilterField extends AbstractTableFilterField
{
	private string $label;
	/** @var FilterOption[] */
	private array $options = [];
	private string $selectedValue = '';
	private bool $chosenEnhancedDropDown;

	public function __construct(string $identifier, string $label, array $options, bool $chosenEnhancedDropDown = false)
	{
		parent::__construct(identifier: $identifier);
		$this->label = $label;

		foreach ($options as $option) {
			if (!($option instanceof FilterOption)) {
				throw new LogicException(message: 'Option must be an instance of FilterOption');
			}
			$this->options[$option->getIdentifier()] = $option;
		}
		$this->chosenEnhancedDropDown = $chosenEnhancedDropDown;
	}

	public function init(): void
	{
		$this->selectedValue = Sanitizer::trimmedString(input: $this->getFromSession(index: $this->getIdentifier()));
	}

	public function reset(): void
	{
		$this->setSelectedValue(selectedValue: '');
	}

	private function setSelectedValue(string $selectedValue): void
	{
		$this->selectedValue = $selectedValue;
		$this->saveToSession(index: $this->getIdentifier(), value: $selectedValue);
	}

	public function checkInput(): void
	{
		$inputValue = Sanitizer::trimmedString(input: HttpRequest::getInstance()->getInputString(keyName: $this->getIdentifier()));
		if (array_key_exists($inputValue, $this->options)) {
			$this->setSelectedValue(selectedValue: $inputValue);
		}
	}

	public function getWhereConditions(): array
	{
		return empty($this->selectedValue) ? [] : [$this->options[$this->selectedValue]->getSqlCondition()];
	}

	public function getSqlParameters(): array
	{
		return empty($this->selectedValue) ? [] : $this->options[$this->selectedValue]->getSqlParams();
	}

	protected function renderField(): string
	{
		$filterName = $this->getIdentifier();
		$filterId = 'filter-' . $filterName;

		$html = $this->label;
		if ($this->chosenEnhancedDropDown) {
			$html .= '<select name="' . $filterName . '" id="' . $filterId . '" class="chosen">';
		} else {
			$html .= '<select name="' . $filterName . '" id="' . $filterId . '">';
		}
		foreach ($this->options as $filterOption) {
			$attributes = ['value="' . $filterOption->getIdentifier() . '"'];
			if ($filterOption->getIdentifier() === $this->selectedValue) {
				$attributes[] = 'selected';
			}

			$html .= '<option ' . implode(' ', $attributes) . '>' . $filterOption->getLabel() . '</option>';
		}
		$html .= '</select>';

		return $html;
	}

	protected function highLightLabel(): bool
	{
		return !empty($this->selectedValue);
	}
}