<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\form\renderer;

use framework\form\component\field\CheckboxOptionsField;
use framework\form\FormRenderer;
use framework\html\HtmlTag;
use framework\html\HtmlTagAttribute;
use framework\html\HtmlText;

class CheckboxItemRenderer extends FormRenderer
{
    public function __construct(private readonly CheckboxOptionsField $checkboxOptionsField)
    {
    }

    public function prepare(): void
    {
        $checkboxOptionsField = $this->checkboxOptionsField;

        $htmlElementDiv = new HtmlTag('div', false, [new HtmlTagAttribute('class', 'form-element', true)]);

        $formItemCheckboxClasses = ['form-item-checkbox'];
        if ($checkboxOptionsField->hasErrors(withChildElements: true)) {
            $formItemCheckboxClasses[] = 'has-error';
        }
        $formItemCheckboxDiv = new HtmlTag(
            'div',
            false,
            [new HtmlTagAttribute('class', implode(separator: ' ', array: $formItemCheckboxClasses), true)]
        );

        $labelTag = new HtmlTag('label', false);
        $labelTag->addTag($this->getInputTag());

        // Create inner "span-label":
        $spanLabelTag = new HtmlTag('span', false, [new HtmlTagAttribute('class', 'label-text', true)]);
        $spanLabelTag->addText($checkboxOptionsField->label);

        $labelTag->addText(HtmlText::encoded(' ' . $spanLabelTag->render()));
        $formItemCheckboxDiv->addTag($labelTag);
        $htmlElementDiv->addTag($formItemCheckboxDiv);

        if (!is_null($checkboxOptionsField->fieldInfo)) {
            FormRenderer::addFieldInfoToParentHtmlTag($checkboxOptionsField, $htmlElementDiv);
        }

        FormRenderer::addErrorsToParentHtmlTag($checkboxOptionsField, $htmlElementDiv);

        $this->setHtmlTag($htmlElementDiv);
    }

    private function getInputTag(): HtmlTag
    {
        $options = $this->checkboxOptionsField->formOptions->data;
        $optionValue = key(array: $options);
        $attributes = [
            new HtmlTagAttribute('type', 'checkbox', true),
            new HtmlTagAttribute('name', $this->checkboxOptionsField->name, true),
            new HtmlTagAttribute('id', $this->checkboxOptionsField->id, true),
            new HtmlTagAttribute('value', $optionValue, true),
        ];

        $checkboxValue = $this->checkboxOptionsField->getRawValue();

        if (is_scalar($checkboxValue) && (string)$checkboxValue == $optionValue) {
            $attributes[] = new HtmlTagAttribute('checked', null, true);
        }

        $ariaDescribedBy = [];

        if ($this->checkboxOptionsField->hasErrors(withChildElements: true)) {
            $attributes[] = new HtmlTagAttribute('aria-invalid', 'true', true);
            $ariaDescribedBy[] = $this->checkboxOptionsField->name . '-error';
        }

        if (!is_null($this->checkboxOptionsField->fieldInfo)) {
            $ariaDescribedBy[] = $this->checkboxOptionsField->name . '-info';
        }

        if (count($ariaDescribedBy) > 0) {
            $attributes[] = new HtmlTagAttribute(
                'aria-describedby',
                implode(separator: ' ', array: $ariaDescribedBy),
                true
            );
        }

        return new HtmlTag('input', true, $attributes);
    }
}