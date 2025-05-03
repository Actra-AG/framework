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
use framework\html\HtmlText;
use LogicException;

abstract class DefaultOptionsRenderer extends FormRenderer
{
    private OptionsField $optionsField;
    private string $inputFieldType;
    private bool $acceptMultipleValues;

    protected function __construct(OptionsField $optionsField, string $inputFieldType, bool $acceptMultipleValues)
    {
        $this->optionsField = $optionsField;
        $this->inputFieldType = $inputFieldType;
        $this->acceptMultipleValues = $acceptMultipleValues;
    }

    public function prepare(): void
    {
        $optionsField = $this->optionsField;
        $options = $optionsField->getFormOptions()->getData();
        if (count($options) === 0) {
            throw new LogicException('There must be at least one option!');
        }

        $listTagClasses = $optionsField->getListTagClasses();
        if ($optionsField->hasErrors(withChildElements: true)) {
            $listTagClasses[] = 'list-has-error';
        }

        $htmlTagAttributes = [];
        if (count($listTagClasses) > 0) {
            $htmlTagAttributes[] = new HtmlTagAttribute('class', implode(separator: ' ', array: $listTagClasses), true);
        }

        $ulTag = new HtmlTag('ul', false, $htmlTagAttributes);

        foreach ($options as $key => $htmlText) {
            $name = ($this->acceptMultipleValues) ? $optionsField->getName() . '[]' : $optionsField->getName();
            $attributes = [
                new HtmlTagAttribute('type', $this->inputFieldType, true),
                new HtmlTagAttribute('name', $name, true),
                new HtmlTagAttribute('id', $optionsField->getId() . '_' . $key, true),
                new HtmlTagAttribute('value', $key, true),
            ];

            $rawValue = $optionsField->getRawValue();

            if ($this->acceptMultipleValues) {
                if (is_array($rawValue) && in_array($key, $rawValue)) {
                    $attributes[] = new HtmlTagAttribute('checked', null, true);
                }
            } else {
                if ($rawValue == $key) {
                    $attributes[] = new HtmlTagAttribute('checked', null, true);
                }
            }

            $inputTag = new HtmlTag('input', true, $attributes);

            // Create inner "span-label":
            $spanLabelTag = new HtmlTag('span', false, [new HtmlTagAttribute('class', 'label-text', true)]);
            $spanLabelTag->addText($htmlText);

            $labelTag = new HtmlTag('label', false);
            $labelTag->addTag($inputTag);
            $labelTag->addText(HtmlText::encoded(' ' . $spanLabelTag->render()));

            $liTag = new HtmlTag('li', false);
            $liTag->addTag($labelTag);
            $ulTag->addTag($liTag);
        }

        $this->setHtmlTag($ulTag);
    }
}