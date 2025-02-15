<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
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