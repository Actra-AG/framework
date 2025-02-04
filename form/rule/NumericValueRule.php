<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\form\rule;

use framework\form\component\FormField;
use framework\form\FormRule;

class NumericValueRule extends FormRule
{
	public function validate(FormField $formField): bool
	{
		if ($formField->isValueEmpty()) {
			return true;
		}

		return is_numeric($formField->getRawValue());
	}
}