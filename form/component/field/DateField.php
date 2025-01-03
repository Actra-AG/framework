<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\form\component\field;

use framework\form\rule\ValidDateRule;
use framework\form\settings\AutoCompleteValue;
use framework\form\settings\InputTypeValue;
use framework\html\HtmlText;

class DateField extends DateTimeFieldCore
{
	public function __construct(
		string             $name,
		HtmlText           $label,
		?string            $value,
		HtmlText           $invalidError,
		?HtmlText          $requiredError = null,
		?string            $placeholder = null,
		?AutoCompleteValue $autoComplete = null
	) {
		parent::__construct(
			inputType: InputTypeValue::DATE,
			name: $name,
			label: $label,
			renderValueFormat: 'Y-m-d',
			value: $value,
			requiredError: $requiredError,
			placeholder: $placeholder,
			autoComplete: $autoComplete
		);
		$this->addRule(formRule: new ValidDateRule(defaultErrorMessage: $invalidError));
	}
}