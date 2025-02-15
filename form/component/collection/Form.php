<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\form\component\collection;

use Exception;
use framework\form\component\field\CsrfTokenField;
use framework\form\component\FormField;
use framework\form\FormCollection;
use framework\form\FormComponent;
use framework\form\FormRenderer;
use framework\form\renderer\DefaultFormRenderer;
use framework\form\renderer\DefinitionListRenderer;
use framework\form\rule\ValidCsrfTokenValue;
use framework\html\HtmlText;
use framework\security\CsrfToken;
use LogicException;

class Form extends FormCollection
{
    private static array $formNameList = [];
    private(set) string $sentIndicator;
    private(set) array $cssClasses = [];
    private bool $renderRequiredAbbr = true;

    public function __construct(
        string $name,
        private(set) readonly bool $acceptUpload = false,
        private(set) readonly ?HtmlText $globalErrorMessage = null,
        private(set) readonly bool $methodPost = true,
        ?string $individualSentIndicator = null
    ) {
        if (in_array(needle: $name, haystack: Form::$formNameList)) {
            throw new LogicException(message: 'A Form with the name "' . $name . '" has already been defined.');
        }
        Form::$formNameList[] = $name;
        $this->sentIndicator = is_null($individualSentIndicator) ? $name : $individualSentIndicator;

        parent::__construct($name);

        $this->addField(new CsrfTokenField());
    }

    public function addField(FormField $formField): void
    {
        if (!$this->renderRequiredAbbr) {
            $formField->renderRequiredAbbr = false;
        }
        $formField->topFormComponent = $this;
        $this->addChildComponent($formField);
    }

    public function removeCsrfProtection(): void
    {
        if ($this->hasChildComponent(CsrfToken::getFieldName())) {
            $this->removeChildComponent(CsrfToken::getFieldName());
        }
    }

    public function getDefaultFormFieldRenderer(FormField $formField): FormRenderer
    {
        return new DefinitionListRenderer(formField: $formField);
    }

    public function addCssClass(string $className): void
    {
        $this->cssClasses[] = $className;
    }

    public function addComponent(FormComponent $formComponent): void
    {
        $this->addChildComponent($formComponent);
    }

    public function removeField(string $name): void
    {
        if (!$this->hasField($name)) {
            throw new Exception('The requested component ' . $name . ' is not an instance of FormField');
        }
        $this->removeChildComponent($name);
    }

    public function hasField(string $name): bool
    {
        if (!$this->hasChildComponent($name)) {
            return false;
        }

        $component = $this->getChildComponent($name);

        return ($component instanceof FormField);
    }

    public function validate(): bool
    {
        if (!$this->isSent()) {
            return false;
        }

        $inputData = ($this->methodPost ? $_POST : $_GET) + $_FILES;

        foreach ($this->childComponents as $formComponent) {
            if (!$formComponent instanceof FormField) {
                continue;
            }

            $formComponent->validate($inputData);
        }

        if (!$this->hasErrors(withChildElements: true)) {
            $this->validateCsrf($inputData);
        }

        if ($this->hasErrors(withChildElements: true) && !$this->hasErrors(withChildElements: false) && !is_null(
                $this->globalErrorMessage
            )) {
            $this->addErrorAsHtmlTextObject($this->globalErrorMessage);
        }

        return !$this->hasErrors(withChildElements: true);
    }

    public function isSent(): bool
    {
        return array_key_exists($this->sentIndicator, $_GET);
    }

    private function validateCsrf(array $inputData): void
    {
        if (!$this->hasChildComponent(CsrfToken::getFieldName())) {
            // The Csrf protection has been disabled
            return;
        }

        /** @var CsrfTokenField $csrfTokenField */
        $csrfTokenField = $this->getField(CsrfToken::getFieldName());

        $validCsrfTokenValue = new ValidCsrfTokenValue();
        $csrfTokenField->addRule($validCsrfTokenValue);

        if (!$csrfTokenField->validate($inputData)) {
            $this->addErrorAsHtmlTextObject($validCsrfTokenValue->getErrorMessage());
        }
    }

    public function getField(string $name): FormField
    {
        $childComponent = $this->getChildComponent($name);

        if (!($childComponent instanceof FormField)) {
            throw new Exception('The requested component ' . $name . ' is not an instance of FormField');
        }

        return $childComponent;
    }

    public function render(): string
    {
        if ($this->hasErrors(withChildElements: true) && !$this->hasErrors(withChildElements: false) && !is_null(
                $this->globalErrorMessage
            )) {
            $this->addErrorAsHtmlTextObject($this->globalErrorMessage);
        }

        return parent::render();
    }

    /**
     * @return FormField[]
     */
    public function getAllFields(): array
    {
        $allFields = [];

        foreach ($this->childComponents as $formComponent) {
            if (!$formComponent instanceof FormField) {
                continue;
            }
            $allFields[] = $formComponent;
        }

        return $allFields;
    }

    public function dontRenderRequiredAbbr(): void
    {
        $this->renderRequiredAbbr = false;
    }

    public function acceptUpload(): bool
    {
        return $this->acceptUpload;
    }

    public function getDefaultRenderer(): FormRenderer
    {
        return new DefaultFormRenderer($this);
    }
}