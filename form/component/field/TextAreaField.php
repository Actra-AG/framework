<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\form\component\field;

use framework\form\component\FormField;
use framework\form\FormRenderer;
use framework\form\renderer\TextAreaRenderer;
use framework\form\rule\RequiredRule;
use framework\html\HtmlEncoder;
use framework\html\HtmlText;

class TextAreaField extends FormField
{
    private(set) int $rows;
    private(set) int $cols;
    private(set) array $cssClassesForRenderer = [];
    private ?string $placeholder = null;

    public function __construct(
        string $name,
        HtmlText $label,
        null|string|array $value = null,
        ?HtmlText $requiredError = null,
        int $rows = 4,
        int $cols = 50
    ) {
        $this->rows = $rows;
        $this->cols = $cols;

        parent::__construct($name, $label, $value);

        if (!is_null($requiredError)) {
            $this->addRule(new RequiredRule($requiredError));
        }
    }

    public function addCssClassForRenderer(string $className): void
    {
        $this->cssClassesForRenderer[] = $className;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    public function setPlaceholder(string $placeholder): void
    {
        $this->placeholder = $placeholder;
    }

    public function getDefaultRenderer(): FormRenderer
    {
        return new TextAreaRenderer($this);
    }

    public function renderValue(): string
    {
        $currentValue = $this->getRawValue();
        if (is_null($currentValue)) {
            return '';
        }
        if (is_array($currentValue)) {
            $htmlArray = [];
            foreach ($currentValue as $row) {
                $htmlArray[] = HtmlEncoder::encode(value: $row);
            }

            return implode(separator: PHP_EOL, array: $htmlArray);
        }

        return HtmlEncoder::encode(value: $currentValue);
    }
}