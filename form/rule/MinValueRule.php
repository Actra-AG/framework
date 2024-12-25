<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\form\rule;

use framework\form\component\FormField;
use framework\form\FormRule;
use framework\html\HtmlText;
use UnexpectedValueException;

class MinValueRule extends FormRule
{
	protected int|float $minValue;

	function __construct(int|float $minValue, HtmlText $errorMessage)
	{
		parent::__construct($errorMessage);

		$this->minValue = $minValue;
	}

	public function validate(FormField $formField): bool
	{
		if ($formField->isValueEmpty()) {
			return true;
		}

		$fieldValue = $formField->getRawValue();

		if (is_int($fieldValue) || is_float($fieldValue)) {
			return ($fieldValue >= $this->minValue);
		}

		throw new UnexpectedValueException('Could not handle field value for rule ' . __CLASS__);
	}
}