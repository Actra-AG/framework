<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\form\component\field;

use framework\form\FormComponent;

class NullField extends FormComponent
{
    public function render(): string
    {
        return '';
    }
}