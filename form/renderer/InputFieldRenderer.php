<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\form\renderer;

use framework\form\component\field\InputField;
use framework\form\component\field\OptionsField;
use framework\form\FormRenderer;
use framework\html\HtmlTag;
use framework\html\HtmlTagAttribute;

class InputFieldRenderer extends FormRenderer
{
    public function __construct(private readonly InputField|OptionsField $formField)
    {
    }

    public function prepare(): void
    {
        $formField = $this->formField;
        $inputTag = new HtmlTag(name: 'input', selfClosing: true);
        $inputTag->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'type',
                value: $formField->inputType->value,
                valueIsEncodedForRendering: true
            )
        );
        $inputTag->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'name', value: $formField->name, valueIsEncodedForRendering: true
            )
        );
        $inputTag->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'id',
                value: $formField->id,
                valueIsEncodedForRendering: true
            )
        );
        $inputTag->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'value',
                value: $formField->renderValue(),
                valueIsEncodedForRendering: true
            )
        );
        if (!is_null(value: $formField->placeholder)) {
            $inputTag->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'placeholder',
                    value: $formField->placeholder,
                    valueIsEncodedForRendering: true
                )
            );
        }
        if (!is_null(value: $formField->autoComplete)) {
            $inputTag->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'autocomplete',
                    value: $formField->autoComplete->value,
                    valueIsEncodedForRendering: true
                )
            );
        }
        if ($formField->autoFocus) {
            $inputTag->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'autofocus',
                    value: null,
                    valueIsEncodedForRendering: true
                )
            );
        }
        FormRenderer::addAriaAttributesToHtmlTag(formField: $formField, parentHtmlTag: $inputTag);
        $this->setHtmlTag(htmlTag: $inputTag);
    }
}