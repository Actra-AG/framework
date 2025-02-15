<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\form\rule;

use ArrayObject;
use framework\form\component\FormField;
use framework\form\FormRule;
use framework\html\HtmlText;
use UnexpectedValueException;

class RegexRule extends FormRule
{
    public function __construct(
        protected string $pattern,
        HtmlText $errorMessage
    ) {
        parent::__construct($errorMessage);
    }

    public function validate(FormField $formField): bool
    {
        if ($formField->isValueEmpty()) {
            return true;
        }

        $fieldValue = $formField->getRawValue();

        if (is_scalar($fieldValue)) {
            return $this->checkAgainstPattern($fieldValue);
        } elseif (is_array($fieldValue) || $fieldValue instanceof ArrayObject) {
            return array_all($fieldValue, fn($value) => $this->checkAgainstPattern($value));
        } else {
            throw new UnexpectedValueException('The field value is neither scalar nor an array');
        }
    }

    protected function checkAgainstPattern($value): bool
    {
        return (preg_match($this->pattern, $value) === 1);
    }
}