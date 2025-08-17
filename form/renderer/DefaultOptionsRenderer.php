<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\form\renderer;

use framework\form\component\field\OptionsField;
use framework\form\FormRenderer;
use framework\html\HtmlTag;
use framework\html\HtmlTagAttribute;
use LogicException;

abstract class DefaultOptionsRenderer extends FormRenderer
{
    protected function __construct(
        private readonly OptionsField $optionsField,
        private readonly string $inputFieldType,
        private readonly bool $acceptMultipleValues
    ) {
    }

    public function prepare(): void
    {
        $optionsField = $this->optionsField;
        $options = $optionsField->getFormOptions()->getData();
        if (count(value: $options) === 0) {
            throw new LogicException(message: 'There must be at least one option!');
        }
        $ulTag = DefaultOptionsRenderer::createUlTag(optionsField: $optionsField);
        foreach ($options as $key => $htmlText) {
            $liTag = new HtmlTag(
                name: 'li',
                selfClosing: false
            );
            $ulTag->addTag(htmlTag: $liTag);
            $liTag->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'class',
                    value: 'form-check',
                    valueIsEncodedForRendering: true
                )
            );
            $inputTag = new HtmlTag(
                name: 'input',
                selfClosing: true
            );
            $inputTag->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'type',
                    value: $this->inputFieldType,
                    valueIsEncodedForRendering: true
                )
            );
            $inputTag->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'name',
                    value: ($this->acceptMultipleValues) ? $optionsField->getName() . '[]' : $optionsField->getName(),
                    valueIsEncodedForRendering: true
                )
            );
            $inputTag->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'id',
                    value: $optionsField->getId() . '_' . $key,
                    valueIsEncodedForRendering: true
                )
            );
            $inputTag->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'value',
                    value: $key,
                    valueIsEncodedForRendering: true
                )
            );
            $rawValue = $optionsField->getRawValue();
            if ($this->acceptMultipleValues) {
                if (
                    is_array(value: $rawValue)
                    && in_array(needle: $key, haystack: $rawValue)
                ) {
                    $inputTag->addHtmlTagAttribute(
                        htmlTagAttribute: new HtmlTagAttribute(
                            name: 'checked',
                            value: null,
                            valueIsEncodedForRendering: true
                        )
                    );
                }
            } elseif ($rawValue == $key) {
                $inputTag->addHtmlTagAttribute(
                    htmlTagAttribute: new HtmlTagAttribute(
                        name: 'checked',
                        value: null,
                        valueIsEncodedForRendering: true
                    )
                );
            }
            $liTag->addTag(htmlTag: $inputTag);
            $labelTag = new HtmlTag(
                name: 'label',
                selfClosing: false
            );
            $labelTag->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'class',
                    value: 'form-check-label',
                    valueIsEncodedForRendering: true
                )
            );
            $labelTag->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'for',
                    value: $optionsField->getId() . '_' . $key,
                    valueIsEncodedForRendering: true
                )
            );
            $labelTag->addText(htmlText: $htmlText);
            $liTag->addTag(htmlTag: $labelTag);
        }

        $this->setHtmlTag(htmlTag: $ulTag);
    }

    public static function createUlTag(OptionsField $optionsField): HtmlTag
    {
        $listTagClasses = $optionsField->getListTagClasses();
        if ($optionsField->hasErrors(withChildElements: true)) {
            $listTagClasses[] = 'list-has-error';
        }
        $htmlTagAttributes = [];
        if (count(value: $listTagClasses) > 0) {
            $htmlTagAttributes[] = new HtmlTagAttribute(
                name: 'class',
                value: implode(
                    separator: ' ',
                    array: $listTagClasses
                ),
                valueIsEncodedForRendering: true
            );
        }

        return new HtmlTag(
            name: 'ul',
            selfClosing: false,
            htmlTagAttributes: $htmlTagAttributes
        );
    }
}