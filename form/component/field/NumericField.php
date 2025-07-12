<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\form\component\field;

use framework\form\FormRenderer;
use framework\form\renderer\NumericFieldRenderer;
use framework\html\HtmlText;

class NumericField extends AmountField
{
    public function __construct(
        public readonly int $minLength,
        ?int $maxLength
    ) {
        parent::__construct(
            name: 'token',
            label: HtmlText::encoded(textContent: '6-stelliger Code'),
            valueIsFloat: false,
            individualInvalidError: HtmlText::encoded(textContent: 'Der angegebene Wert ist ungültig.'),
            requiredError: HtmlText::encoded(textContent: 'Bitte gib den 6-stelligen Code ein.'),
            maxLength: $maxLength
        );
    }

    public function getDefaultRenderer(): FormRenderer
    {
        return new NumericFieldRenderer(numericField: $this);
    }
}