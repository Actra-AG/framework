<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\form\rule;

use framework\form\component\FormField;
use framework\form\FormRule;

class FloatValueRule extends FormRule
{
	public function validate(FormField $formField): bool
	{
		if ($formField->isValueEmpty()) {
			return true;
		}

		$value = $formField->getRawValue();

		return (
			filter_var($value, FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND) !== false ||
			filter_var($value, FILTER_VALIDATE_FLOAT, [
				'flags'   => FILTER_FLAG_ALLOW_THOUSAND,
				'options' => ['decimal' => ','],
			]) !== false
		);
	}
}