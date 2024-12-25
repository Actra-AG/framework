<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\form\rule;

use framework\form\component\FormField;
use framework\form\FormRule;

class RequiredRule extends FormRule
{
	public function validate(FormField $formField): bool
	{
		return !$formField->isValueEmpty();
	}
}