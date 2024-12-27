<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\form\rule;

use framework\form\component\field\PhoneNumberField;
use framework\form\component\FormField;
use framework\form\FormRule;
use framework\phone\PhoneNumber;
use framework\phone\PhoneParseException;
use framework\phone\PhoneRenderer;
use LogicException;

class PhoneNumberRule extends FormRule
{
	public function validate(FormField $formField): bool
	{
		if (!($formField instanceof PhoneNumberField)) {
			throw new LogicException(message: 'The formField must be an instance of PhoneNumberField');
		}
		if ($formField->isValueEmpty()) {
			return true;
		}
		try {
			$phoneNumber = PhoneNumber::createFromString(
				input: $formField->getRawValue(),
				defaultCountryCode: $formField->getCountryCode()
			);
		} catch (PhoneParseException) {
			return false;
		}
		$formField->setValue(value: PhoneRenderer::renderInternalFormat(phoneNumber: $phoneNumber));

		return true;
	}
}