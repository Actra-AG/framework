<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\form\renderer;

use framework\form\component\field\NumericField;
use framework\html\HtmlTagAttribute;

class NumericFieldRenderer extends InputFieldRenderer
{
    public function __construct(private readonly NumericField $numericField)
    {
        parent::__construct(formField: $numericField);
    }

    public function prepare(): void
    {
        parent::prepare();
        $inputTag = $this->getHtmlTag();
        $inputTag->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'inputmode',
                value: 'numeric',
                valueIsEncodedForRendering: true
            )
        );
        $inputTag->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'pattern',
                value: '\d{' . $this->getDigitQuantifier() . '}',
                valueIsEncodedForRendering: true
            )
        );
    }

    private function getDigitQuantifier(): string
    {
        $minLength = $this->numericField->minLength;
        $maxLength = $this->numericField->maxLength;
        if ($minLength === $maxLength) {
            return $this->numericField->minLength;
        }
        if (is_null(value: $maxLength)) {
            return $minLength . ',';
        }
        return $minLength . ',' . $maxLength;
    }
}