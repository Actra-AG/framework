<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\form\renderer;

use framework\form\component\field\HiddenField;
use framework\form\FormRenderer;
use framework\html\HtmlTag;
use framework\html\HtmlTagAttribute;

class HiddenFieldRenderer extends FormRenderer
{
    public function __construct(private readonly HiddenField $hiddenField)
    {
    }

    public function prepare(): void
    {
        $hiddenField = $this->hiddenField;
        $this->setHtmlTag(htmlTag: new HtmlTag(name: 'input', selfClosing: true, htmlTagAttributes: [
            new HtmlTagAttribute(name: 'type', value: $hiddenField->inputType->value, valueIsEncodedForRendering: true),
            new HtmlTagAttribute(name: 'name', value: $hiddenField->getName(), valueIsEncodedForRendering: true),
            new HtmlTagAttribute(name: 'value', value: $hiddenField->renderValue(), valueIsEncodedForRendering: true),
        ]));
    }
}