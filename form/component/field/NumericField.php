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
        string $name,
        HtmlText $label,
        null|int|float $initialValue = null,
        ?HtmlText $individualInvalidError = null,
        ?HtmlText $requiredError = null,
        ?string $placeholder = null,
        public readonly int $minLength,
        ?int $maxLength = null
    ) {
        parent::__construct(
            name: $name,
            label: $label,
            valueIsFloat: false,
            initialValue: $initialValue,
            individualInvalidError: $individualInvalidError,
            requiredError: $requiredError,
            placeholder: $placeholder,
            maxLength: $maxLength
        );
    }

    public function getDefaultRenderer(): FormRenderer
    {
        return new NumericFieldRenderer(numericField: $this);
    }
}