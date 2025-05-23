<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\form\renderer;

use framework\form\component\field\SelectOptionsField;
use framework\form\FormRenderer;
use framework\html\HtmlTag;
use framework\html\HtmlTagAttribute;
use LogicException;

class SelectOptionsRenderer extends FormRenderer
{
    public function __construct(private readonly SelectOptionsField $selectOptionsField)
    {
    }

    public function prepare(): void
    {
        $selectOptionsField = $this->selectOptionsField;
        $selectedValue = $selectOptionsField->getRawValue();
        if ($selectOptionsField->acceptMultipleSelections && !is_array(value: $selectedValue)) {
            throw new LogicException(
                message: 'The selected value must be an array if selection of multiple elements is allowed'
            );
        }
        $fieldName = $selectOptionsField->name;
        $selectTag = new HtmlTag(name: 'select', selfClosing: false);
        $selectTag->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'name',
                value: $selectOptionsField->acceptMultipleSelections ? $fieldName . '[]' : $fieldName,
                valueIsEncodedForRendering: true
            )
        );
        $selectTag->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'id',
                value: $selectOptionsField->id,
                valueIsEncodedForRendering: true
            )
        );
        if (count(value: $selectOptionsField->cssClasses) > 0) {
            $selectTag->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'class',
                    value: implode(separator: ' ', array: $selectOptionsField->cssClasses),
                    valueIsEncodedForRendering: true
                )
            );
        }
        if ($selectOptionsField->acceptMultipleSelections) {
            $selectTag->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'multiple',
                    value: null,
                    valueIsEncodedForRendering: true
                )
            );
        }
        if (!is_null(value: $selectOptionsField->placeholder)) {
            $selectTag->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'placeholder',
                    value: $selectOptionsField->placeholder,
                    valueIsEncodedForRendering: true
                )
            );
        }
        if (!is_null(value: $selectOptionsField->autoComplete)) {
            $selectTag->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'autocomplete',
                    value: $selectOptionsField->autoComplete->value,
                    valueIsEncodedForRendering: true
                )
            );
        }
        $options = $selectOptionsField->formOptions->data;
        if (
            $selectOptionsField->renderEmptyValueOption
            && !array_key_exists(key: '', array: $options)
        ) {
            $options = ['' => $selectOptionsField->emptyValueLabel] + $options;
        }
        foreach ($options as $key => $htmlText) {
            $attributes = [new HtmlTagAttribute(name: 'value', value: $key, valueIsEncodedForRendering: true)];
            if (
                ($selectOptionsField->acceptMultipleSelections && in_array(needle: $key, haystack: $selectedValue))
                || (!$selectOptionsField->acceptMultipleSelections && 'selected_' . $key === 'selected_' . $selectedValue)
            ) {
                $attributes[] = new HtmlTagAttribute(name: 'selected', value: null, valueIsEncodedForRendering: true);
            }
            $selectTag->addTag(
                htmlTag: $optionTag = new HtmlTag(
                    name: 'option',
                    selfClosing: false,
                    htmlTagAttributes: $attributes
                )
            );
            $optionTag->addText(htmlText: $htmlText);
        }
        $this->setHtmlTag(htmlTag: $selectTag);
    }
}