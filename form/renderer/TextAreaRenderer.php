<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\form\renderer;

use framework\form\component\field\TextAreaField;
use framework\form\FormRenderer;
use framework\html\HtmlEncoder;
use framework\html\HtmlTag;
use framework\html\HtmlTagAttribute;
use framework\html\HtmlText;

class TextAreaRenderer extends FormRenderer
{
    private TextAreaField $textAreaField;

    public function __construct(TextAreaField $textAreaField)
    {
        $this->textAreaField = $textAreaField;
    }

    public function prepare(): void
    {
        $textAreaField = $this->textAreaField;

        $attributes = [
            new HtmlTagAttribute('name', $textAreaField->name, true),
            new HtmlTagAttribute('id', $textAreaField->id, true),
            new HtmlTagAttribute('rows', $textAreaField->rows, true),
            new HtmlTagAttribute('cols', $textAreaField->cols, true),
        ];

        $cssClassesForRenderer = $textAreaField->cssClassesForRenderer;
        if (count($cssClassesForRenderer) > 0) {
            $attributes[] = new HtmlTagAttribute('class', implode(separator: ' ', array: $cssClassesForRenderer), true);
        }

        if (!is_null($textAreaField->getPlaceholder())) {
            $attributes[] = new HtmlTagAttribute('placeholder', $textAreaField->getPlaceholder(), true);
        }

        $textareaTag = new HtmlTag('textarea', false, $attributes);
        FormRenderer::addAriaAttributesToHtmlTag($textAreaField, $textareaTag);

        $value = $textAreaField->getRawValue();
        if (is_array($value)) {
            $rows = [];
            foreach ($value as $row) {
                $rows[] = HtmlEncoder::encode(value: $row);
            }
            $html = implode(separator: PHP_EOL, array: $rows);
        } else {
            $html = HtmlEncoder::encode(value: $value);
        }

        $textareaTag->addText(HtmlText::encoded($html));

        $this->setHtmlTag($textareaTag);
    }
}