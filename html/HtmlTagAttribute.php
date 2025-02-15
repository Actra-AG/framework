<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\html;

class HtmlTagAttribute extends HtmlElement
{
    public ?string $value;
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
            return $this->name;
        }

        $renderValue = $this->valueIsEncodedForRendering ? $this->value : HtmlEncoder::encode(value: $this->value);

        return $this->name . '="' . $renderValue . '"';
    }

}