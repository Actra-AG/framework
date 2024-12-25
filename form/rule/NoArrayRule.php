<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\form\rule;

use framework\form\component\FormField;
use framework\form\FormRule;

class NoArrayRule extends FormRule
{
	public function validate(FormField $formField): bool
	{
		return !is_array($formField->getRawValue());
	}
}