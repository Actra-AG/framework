<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\html;

class HtmlTagAttribute extends HtmlElement
{
    private ?string $value; // The value for this attribute. null = no value (e.g. if name is required, selected or checked)
    private bool $valueIsEncodedForRendering;

    public function __construct(string $name, ?string $value, bool $valueIsEncodedForRendering)
    {
        $this->value = $value;
        $this->valueIsEncodedForRendering = $valueIsEncodedForRendering;
        parent::__construct($name);
    }

    /**
     * Generate the html-code for this Attribute-Element to be used for output
     *
     * @return string : Generated html-code
     */
    public function render(): string
    {
        if (is_null($this->value)) {
            return $this->getName();
        }

        $renderValue = $this->valueIsEncodedForRendering ? $this->value : HtmlEncoder::encode(value: $this->value);

        return $this->getName() . '="' . $renderValue . '"';
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }
}