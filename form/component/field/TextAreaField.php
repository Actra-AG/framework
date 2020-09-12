<?php
/**
 * @author    Christof Moser <christof.moser@actra.ch>
 * @copyright Copyright (c) 2020, Actra AG
 */

namespace framework\form\component\field;

use framework\form\component\FormField;
use framework\form\FormRenderer;
use framework\form\renderer\TextAreaRenderer;
use framework\form\rule\RequiredRule;

class TextAreaField extends FormField
{
	private int $rows;
	private int $cols;
	private ?string $placeholder = null;
	private array $cssClassesForRenderer = [];
	private ?int $maxlength;

	public function __construct(string $name, string $label, $value = null, ?string $requiredError = null, int $rows = 4, int $cols = 50, ?int $maxlength = null)
	{
		$this->rows = $rows;
		$this->cols = $cols;
		$this->maxlength = $maxlength;

		parent::__construct($name, $label, $value);

		if (!is_null($requiredError)) {
			$this->addRule(new RequiredRule($requiredError));
		}

	}

	public function addCssClassForRenderer(string $className)
	{
		$this->cssClassesForRenderer[] = $className;
	}

	public function getCssClassesForRenderer(): array
	{
		return $this->cssClassesForRenderer;
	}

	public function setPlaceholder(string $placeholder)
	{
		$this->placeholder = $placeholder;
	}

	public function getRows(): int
	{
		return $this->rows;
	}

	public function getCols(): int
	{
		return $this->cols;
	}

	public function getMaxlength(): ?int
	{
		return $this->maxlength;
	}

	public function getPlaceholder(): ?string
	{
		return $this->placeholder;
	}

	public function getDefaultRenderer(): FormRenderer
	{
		return new TextAreaRenderer($this);
	}

	public function renderValue(): string
	{
		$currentValue = $this->getRawValue();
		if (is_null($currentValue)) {
			return '';
		}
		if (is_array($currentValue)) {
			$htmlArray = [];
			foreach ($currentValue as $row) {
				$htmlArray[] = FormRenderer::htmlEncode($row);
			}

			return implode(PHP_EOL, $htmlArray);
		}

		return FormRenderer::htmlEncode($currentValue);
	}
}
/* EOF */