<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
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
	private bool $acceptUpload;
	private ?HtmlText $globalErrorMessage;
	private bool $methodPost;
	private string $sentIndicator;
	private array $cssClasses = [];
	private bool $renderRequiredAbbr = true;

	public function __construct(
		string    $name,
		bool      $acceptUpload = false,
		?HtmlText $globalErrorMessage = null,
		bool      $methodPost = true,
		?string   $individualSentIndicator = null
	) {
		if (in_array($name, Form::$formNameList)) {
			throw new LogicException('A Form with the name "' . $name . '" has already been defined.');
		}
		Form::$formNameList[] = $name;

		$this->acceptUpload = $acceptUpload;
		$this->globalErrorMessage = $globalErrorMessage;
		$this->methodPost = $methodPost;
		$this->sentIndicator = is_null($individualSentIndicator) ? $name : $individualSentIndicator;

		parent::__construct($name);

		$this->addField(new CsrfTokenField());
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

	public function addField(FormField $formField): void
	{
		if (!$this->renderRequiredAbbr) {
			$formField->setRenderRequiredAbbr(false);
		}
		$formField->setTopFormComponent($this);
		$this->addChildComponent($formField);
	}

	public function hasField(string $name): bool
	{
		if (!$this->hasChildComponent($name)) {
			return false;
		}

		$component = $this->getChildComponent($name);

		return ($component instanceof FormField);
	}

	public function getField(string $name): FormField
	{
		$childComponent = $this->getChildComponent($name);

		if (!($childComponent instanceof FormField)) {
			throw new Exception('The requested component ' . $name . ' is not an instance of FormField');
		}

		return $childComponent;
	}

	public function removeField(string $name): void
	{
		if (!$this->hasField($name)) {
			throw new Exception('The requested component ' . $name . ' is not an instance of FormField');
		}
		$this->removeChildComponent($name);
	}

	public function isSent(): bool
	{
		return array_key_exists($this->sentIndicator, $_GET);
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
			$this->validateCsrf($inputData);
		}

		if ($this->hasErrors(withChildElements: true) && !$this->hasErrors(withChildElements: false) && !is_null($this->globalErrorMessage)) {
			$this->addErrorAsHtmlTextObject($this->globalErrorMessage);
		}

		return !$this->hasErrors(withChildElements: true);
	}

	public function render(): string
	{
		if ($this->hasErrors(withChildElements: true) && !$this->hasErrors(withChildElements: false) && !is_null($this->globalErrorMessage)) {
			$this->addErrorAsHtmlTextObject($this->globalErrorMessage);
		}

		return parent::render();
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

	public function isMethodPost(): bool
	{
		return $this->methodPost;
	}

	public function getSentIndicator(): string
	{
		return $this->sentIndicator;
	}

	public function getCssClasses(): array
	{
		return $this->cssClasses;
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