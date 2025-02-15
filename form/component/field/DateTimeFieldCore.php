<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\form\component\field;

use DateTimeImmutable;
use framework\datacheck\Sanitizer;
use framework\form\rule\RequiredRule;
use framework\form\settings\AutoCompleteValue;
use framework\form\settings\InputTypeValue;
use framework\html\HtmlEncoder;
use framework\html\HtmlText;
use Throwable;

abstract class DateTimeFieldCore extends InputField
{
    public function __construct(
        InputTypeValue $inputType,
        string $name,
        HtmlText $label,
        private string $renderValueFormat,
        ?string $value = null,
        ?HtmlText $requiredError = null,
        ?string $placeholder = null,
        ?AutoCompleteValue $autoComplete = null
    ) {
        parent::__construct(
            inputType: $inputType,
            name: $name,
            label: $label,
            value: $value,
            placeholder: $placeholder,
            autoComplete: $autoComplete
        );
        if (!is_null(value: $requiredError)) {
            $this->addRule(formRule: new RequiredRule(defaultErrorMessage: $requiredError));
        }
    }

    public function renderValue(): string
    {
        if ($this->isValueEmpty()) {
            return '';
        }
        $originalValue = $this->getRawValue();
        if ($this->hasErrors(withChildElements: false)) {
            // Invalid value; show original input
            return HtmlEncoder::encode(value: Sanitizer::trimmedString(input: $originalValue));
        }
        try {
            return (new DateTimeImmutable(datetime: $originalValue))->format(format: $this->renderValueFormat);
        } catch (Throwable) {
            // Should not be reached. Anyway ... invalid value; show original input
            return HtmlEncoder::encode(value: Sanitizer::trimmedString(input: $originalValue));
        }
    }
}