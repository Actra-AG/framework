<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\form\rule;

use framework\common\ValidatedEmailAddress;
use framework\form\component\FormField;
use framework\form\FormRule;
use framework\html\HtmlText;

class ValidEmailAddressRule extends FormRule
{
    public function __construct(
        HtmlText $errorMessage,
        private readonly bool $dnsCheck = true,
        private readonly bool $trueOnDnsError = true
    ) {
        parent::__construct(defaultErrorMessage: $errorMessage);
    }

    public function validate(FormField $formField): bool
    {
        if ($formField->isValueEmpty()) {
            return true;
        }
        $fieldValue = (string)$formField->getRawValue();
        $validatedEmailAddress = new ValidatedEmailAddress(emailAddress: $fieldValue);
        if (!$validatedEmailAddress->isValidSyntax) {
            return false;
        }
        $formField->setValue(value: $validatedEmailAddress->validatedValue);
        if (!$this->dnsCheck) {
            return true;
        }

        return $validatedEmailAddress->isResolvable(returnTrueOnDnsGetRecordFailure: $this->trueOnDnsError);
    }
}