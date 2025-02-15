<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\form\component;

use ArrayObject;
use DateTime;
use framework\form\component\collection\Form;
use framework\form\FormComponent;
use framework\form\FormRule;
use framework\form\listener\FormFieldListener;
use framework\form\rule\RequiredRule;
use framework\html\HtmlEncoder;
use framework\html\HtmlText;
use UnexpectedValueException;

abstract class FormField extends FormComponent
{
    /** @var FormFieldListener[] */
    protected array $listeners = [];
    protected ?HtmlText $fieldInfo = null;
    protected ?HtmlText $labelInfoText = null;
    protected ?HtmlText $additionalColumnContent = null;
    protected Form $topFormComponent;
    protected bool $renderRequiredAbbr = true;
    private string $id;
    private HtmlText $label;
    private mixed $value;
    private mixed $originalValue = null;

    // Renderer options:
    /** @var FormRule[] */
    private array $rules = [];
    private bool $renderLabel = true;
    private bool $acceptArrayAsValue = false;
    private bool $autoFocus = false;

    /**
     * @param string $name The internal name for this formField which is also used by the renderer (name="")
     * @param HtmlText $label The field label to be used by the renderer
     * @param mixed $value The original value for this formField. Depending on the specific field it can be a string, float, integer and even an
     *                                 array. By default it is null.
     * @param ?HtmlText $labelInfoText Additional text padded to the displayed label-name (see FileField max-Info for example)
     */
    public function __construct(string $name, HtmlText $label, mixed $value = null, ?HtmlText $labelInfoText = null)
    {
        $this->id = $name;
        $this->label = $label;
        parent::__construct($name);

        if (is_array($value)) {
            // If the value to be pre-filled is already an array we can also accept an array as user input
            $this->acceptArrayAsValue();
        }

        $this->setValue($value);
        $this->setOriginalValue($value);
        $this->labelInfoText = $labelInfoText;
    }

    protected function acceptArrayAsValue(): void
    {
        $this->acceptArrayAsValue = true;
    }

    public function setValue($value): void
    {
        if (is_array($value) && !$this->isArrayAsValueAllowed()) {
            $this->addError('Die ungültige Eingabe wurde ignoriert.', true);

            return;
        }

        if (is_string($value)) {
            $value = str_replace("\xE2\x80\x8B", '', $value);
        }

        $this->value = $value;
    }

    protected function isArrayAsValueAllowed(): bool
    {
        return $this->acceptArrayAsValue;
    }

    public function isAutoFocus(): bool
    {
        return $this->autoFocus;
    }

    public function setAutoFocus(): void
    {
        $this->autoFocus = true;
    }

    public function setTopFormComponent(Form $topFormComponent): void
    {
        $this->topFormComponent = $topFormComponent;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getFieldInfo(): ?HtmlText
    {
        return $this->fieldInfo;
    }

    public function setFieldInfo(?HtmlText $fieldInfo): void
    {
        $this->fieldInfo = $fieldInfo;
    }

    public function getAdditionalColumnContent(): ?HtmlText
    {
        return $this->additionalColumnContent;
    }

    public function setAdditionalColumnContent(?HtmlText $additionalColumnContent): void
    {
        $this->additionalColumnContent = $additionalColumnContent;
    }

    public function getLabel(): HtmlText
    {
        return $this->label;
    }

    public function getLabelInfoText(): ?HtmlText
    {
        return $this->labelInfoText;
    }

    public function setLabelInfoText(?HtmlText $labelInfoText): void
    {
        $this->labelInfoText = $labelInfoText;
    }

    public function renderValue(): string
    {
        return HtmlEncoder::encode(value: $this->getRawValue());
    }

    public function getRawValue(bool $returnNullIfEmpty = false)
    {
        if ($this->isValueEmpty() && $returnNullIfEmpty) {
            return null;
        }

        return $this->value;
    }

    public function isValueEmpty(): bool
    {
        if ($this->value === null) {
            return true;
        }

        if (is_scalar($this->value)) {
            return (strlen(string: trim(string: $this->value)) <= 0);
        } else {
            if (is_array($this->value)) {
                return (count(array_filter($this->value)) <= 0);
            } else {
                if ($this->value instanceof ArrayObject) {
                    return (count(array_filter((array)$this->value)) <= 0);
                } else {
                    if ($this->value instanceof DateTime) {
                        return false;
                    } else {
                        throw new UnexpectedValueException('Could not check value against emptiness');
                    }
                }
            }
        }
    }

    public function getOriginalValue()
    {
        return $this->originalValue;
    }

    public function setOriginalValue($value): void
    {
        $this->originalValue = $value;
    }

    public function getAddedValues(): array
    {
        if (!is_array($this->value) || !is_array($this->originalValue)) {
            return [];
        }

        $addedValues = [];

        foreach ($this->value as $selectedValue) {
            if (!in_array($selectedValue, $this->originalValue)) {
                $addedValues[] = $selectedValue;
            }
        }

        return $addedValues;
    }

    public function getRemovedValues(): array
    {
        if (!is_array($this->value) || !is_array($this->originalValue)) {
            return [];
        }

        $removedValues = [];

        foreach ($this->originalValue as $originalValue) {
            if (!in_array($originalValue, $this->value)) {
                $removedValues[] = $originalValue;
            }
        }

        return $removedValues;
    }

    public function addRequiredRule(HtmlText $errorMessage): void
    {
        $this->addRule(new RequiredRule($errorMessage));
    }

    public function addRule(FormRule $formRule): void
    {
        $this->rules[] = $formRule;
    }

    public function isRequired(): bool
    {
        return $this->hasRule(ruleClassName: RequiredRule::class);
    }

    protected function hasRule(string $ruleClassName): bool
    {
        foreach ($this->rules as $rule) {
            if (get_class(object: $rule) === $ruleClassName) {
                return true;
            }
        }

        return false;
    }

    public function addListener(FormFieldListener $formFieldListener): void
    {
        $this->listeners[] = $formFieldListener;
    }

    /**
     * Use the rules to validate the input data.
     *
     * @param array $inputData : All input data
     * @param bool $overwriteValue : Overwrite current value by value from inputData (true by default)
     *
     * @return bool : Validation result (false on error)
     */
    public function validate(array $inputData, bool $overwriteValue = true): bool
    {
        if ($overwriteValue) {
            $defaultValue = $this->isArrayAsValueAllowed() ? [] : null;
            $this->setValue(
                array_key_exists($this->getName(), $inputData) ? $inputData[$this->getName()] : $defaultValue
            );
        }

        foreach ($this->listeners as $formFieldListener) {
            if ($this->isValueEmpty()) {
                $formFieldListener->onEmptyValueBeforeValidation($this->topFormComponent, $this);
            } else {
                $formFieldListener->onNotEmptyValueBeforeValidation($this->topFormComponent, $this);
            }
        }

        foreach ($this->rules as $formRule) {
            if (!$formRule->validate($this)) {
                $this->addErrorAsHtmlTextObject($formRule->getErrorMessage());
            }
        }

        $hasErrors = $this->hasErrors(withChildElements: false);
        foreach ($this->listeners as $formFieldListener) {
            if ($this->isValueEmpty()) {
                $formFieldListener->onEmptyValueAfterValidation($this->topFormComponent, $this);
            } else {
                $formFieldListener->onNotEmptyValueAfterValidation($this->topFormComponent, $this);
            }

            if ($hasErrors) {
                $formFieldListener->onValidationError($this->topFormComponent, $this);
            } else {
                $formFieldListener->onValidationSuccess($this->topFormComponent, $this);
            }
        }

        return !$this->hasErrors(withChildElements: true);
    }

    /**
     * Suppresses the VISIBLE label rendering of associated input field
     * (It will be still readable by screen readers)
     */
    public function setRenderLabelFalse(): void
    {
        $this->renderLabel = false;
    }

    public function isRenderLabel(): bool
    {
        return $this->renderLabel;
    }

    public function isRenderRequiredAbbr(): bool
    {
        return $this->renderRequiredAbbr;
    }

    public function setRenderRequiredAbbr(bool $renderRequiredAbbr): void
    {
        $this->renderRequiredAbbr = $renderRequiredAbbr;
    }

    /**
     * Returns whether the original value has changed (true) or not (false)
     *
     * @return bool
     */
    public function valueHasChanged(): bool
    {
        return ($this->value !== $this->originalValue);
    }
}