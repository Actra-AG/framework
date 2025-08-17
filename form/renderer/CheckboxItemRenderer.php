<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\form\renderer;

use framework\form\component\field\CheckboxOptionsField;
use framework\form\FormRenderer;
use framework\html\HtmlTag;
use framework\html\HtmlTagAttribute;

class CheckboxItemRenderer extends FormRenderer
{
    public function __construct(private readonly CheckboxOptionsField $checkboxOptionsField)
    {
    }

    public function prepare(): void
    {
        $checkboxOptionsField = $this->checkboxOptionsField;

        $divFormCheck = new HtmlTag(
            name: 'div',
            selfClosing: false
        );
        $formItemCheckboxClasses = ['form-check'];
        if ($checkboxOptionsField->hasErrors(withChildElements: true)) {
            $formItemCheckboxClasses[] = 'has-error';
        }
        $divFormCheck->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'class',
                value: implode(
                    separator: ' ',
                    array: $formItemCheckboxClasses
                ),
                valueIsEncodedForRendering: true
            )
        );
        $divFormCheck->addTag(htmlTag: $this->getInputTag());
        $labelTag = new HtmlTag(
            name: 'label',
            selfClosing: false
        );
        $labelTag->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'for',
                value: $this->checkboxOptionsField->getId(),
                valueIsEncodedForRendering: true
            )
        );
        $labelTag->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'class',
                value: 'form-check-label',
                valueIsEncodedForRendering: true
            )
        );
        $labelTag->addText(htmlText: $checkboxOptionsField->getLabel());
        $divFormCheck->addTag(htmlTag: $labelTag);
        if (!is_null(value: $checkboxOptionsField->getFieldInfo())) {
            FormRenderer::addFieldInfoToParentHtmlTag(
                formFieldWithFieldInfo: $checkboxOptionsField,
                parentHtmlTag: $divFormCheck
            );
        }
        FormRenderer::addErrorsToParentHtmlTag(
            formComponentWithErrors: $checkboxOptionsField,
            parentHtmlTag: $divFormCheck
        );
        $this->setHtmlTag(htmlTag: $divFormCheck);
    }

    private function getInputTag(): HtmlTag
    {
        $inputTag = new HtmlTag(
            name: 'input',
            selfClosing: true
        );
        $inputTag->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'type',
                value: 'checkbox',
                valueIsEncodedForRendering: true
            )
        );
        $inputTag->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'name',
                value: $this->checkboxOptionsField->getName(),
                valueIsEncodedForRendering: true
            )
        );
        $inputTag->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'id',
                value: $this->checkboxOptionsField->getId(),
                valueIsEncodedForRendering: true
            )
        );
        $options = $this->checkboxOptionsField->getFormOptions()->getData();
        $optionValue = key(array: $options);
        $inputTag->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'value',
                value: $optionValue,
                valueIsEncodedForRendering: true
            )
        );
        $checkboxValue = $this->checkboxOptionsField->getRawValue();
        if (
            is_scalar(value: $checkboxValue)
            && (string)$checkboxValue == $optionValue
        ) {
            $inputTag->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'checked',
                    value: null,
                    valueIsEncodedForRendering: true
                )
            );
        }
        $ariaDescribedBy = [];
        if ($this->checkboxOptionsField->hasErrors(withChildElements: true)) {
            $inputTag->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'aria-invalid',
                    value: 'true',
                    valueIsEncodedForRendering: true
                )
            );
            $ariaDescribedBy[] = $this->checkboxOptionsField->getName() . '-error';
        }
        if (!is_null(value: $this->checkboxOptionsField->getFieldInfo())) {
            $ariaDescribedBy[] = $this->checkboxOptionsField->getName() . '-info';
        }
        if (count(value: $ariaDescribedBy) > 0) {
            $inputTag->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'aria-describedby',
                    value: implode(
                        separator: ' ',
                        array: $ariaDescribedBy
                    ),
                    valueIsEncodedForRendering: true
                )
            );
        }

        return $inputTag;
    }
}