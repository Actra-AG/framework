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
    public readonly string $sentIndicator;
    private array $cssClasses = [];
    private bool $renderRequiredAbbr = true;

    public function __construct(
        string $name,
        public readonly bool $acceptUpload = false,
        public readonly ?HtmlText $globalErrorMessage = null,
        public readonly bool $methodPost = true,
        ?string $individualSentIndicator = null,
        public readonly bool $disableClientValidation = false
    ) {
        if (in_array(
            needle: $name,
            haystack: Form::$formNameList
        )) {
            throw new LogicException(message: 'A Form with the name "' . $name . '" has already been defined.');
        }
        Form::$formNameList[] = $name;
        $this->sentIndicator = is_null(value: $individualSentIndicator) ? $name : $individualSentIndicator;
        parent::__construct(name: $name);

        $this->addField(formField: new CsrfTokenField());
    }

    public function addField(FormField $formField): void
    {
        if (!$this->renderRequiredAbbr) {
            $formField->setRenderRequiredAbbr(renderRequiredAbbr: false);
        }
        $formField->setTopFormComponent(topFormComponent: $this);
        $this->addChildComponent(formComponent: $formField);
    }

    public function removeCsrfProtection(): void
    {
        if ($this->hasChildComponent(childComponentName: CsrfToken::getFieldName())) {
            $this->removeChildComponent(childComponentName: CsrfToken::getFieldName());
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
        $this->addChildComponent(formComponent: $formComponent);
    }

    public function removeField(string $name): void
    {
        if (!$this->hasField(name: $name)) {
            throw new Exception(message: 'The requested component ' . $name . ' is not an instance of FormField');
        }
        $this->removeChildComponent(childComponentName: $name);
    }

    public function hasField(string $name): bool
    {
        if (!$this->hasChildComponent(childComponentName: $name)) {
            return false;
        }

        $component = $this->getChildComponent(childComponentName: $name);

        return ($component instanceof FormField);
    }

    public function validate(): bool
    {
        if (!$this->isSent()) {
            return false;
        }

        $inputData = ($this->methodPost ? $_POST : $_GET) + $_FILES;

        foreach ($this->getChildComponents() as $formComponent) {
            if (!$formComponent instanceof FormField) {
                continue;
            }

            $formComponent->validate($inputData);
        }

        if (!$this->hasErrors(withChildElements: true)) {
            $this->validateCsrf(inputData: $inputData);
        }

        if (
            $this->hasErrors(withChildElements: true)
            && !$this->hasErrors(withChildElements: false)
            && !is_null(value: $this->globalErrorMessage)
        ) {
            $this->addErrorAsHtmlTextObject(errorMessageObject: $this->globalErrorMessage);
        }

        return !$this->hasErrors(withChildElements: true);
    }

    public function isSent(): bool
    {
        return array_key_exists(
            key: $this->sentIndicator,
            array: $_GET
        );
    }

    private function validateCsrf(array $inputData): void
    {
        if (!$this->hasChildComponent(childComponentName: CsrfToken::getFieldName())) {
            // The Csrf protection has been disabled
            return;
        }
        /** @var CsrfTokenField $csrfTokenField */
        $csrfTokenField = $this->getField(name: CsrfToken::getFieldName());
        $validCsrfTokenValue = new ValidCsrfTokenValue();
        $csrfTokenField->addRule(formRule: $validCsrfTokenValue);
        if (!$csrfTokenField->validate(inputData: $inputData)) {
            $this->addErrorAsHtmlTextObject(errorMessageObject: $validCsrfTokenValue->getErrorMessage());
        }
    }

    public function getField(string $name): FormField
    {
        $childComponent = $this->getChildComponent(childComponentName: $name);
        if (!($childComponent instanceof FormField)) {
            throw new Exception(message: 'The requested component ' . $name . ' is not an instance of FormField');
        }

        return $childComponent;
    }

    public function render(): string
    {
        if (
            $this->hasErrors(withChildElements: true)
            && !$this->hasErrors(withChildElements: false)
            && !is_null(
                $this->globalErrorMessage
            )
        ) {
            $this->addErrorAsHtmlTextObject(errorMessageObject: $this->globalErrorMessage);
        }

        return parent::render();
    }

    /**
     * @return FormField[]
     */
    public function getAllFields(): array
    {
        $allFields = [];
        foreach ($this->getChildComponents() as $formComponent) {
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


    public function getCssClasses(): array
    {
        return $this->cssClasses;
    }

    public function getDefaultRenderer(): FormRenderer
    {
        return new DefaultFormRenderer(form: $this);
    }
}