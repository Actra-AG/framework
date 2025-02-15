<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\form\rule;

use DateTimeImmutable;
use framework\form\component\FormField;
use framework\form\FormRule;
use Throwable;

class ValidDateRule extends FormRule
{
    public function validate(FormField $formField): bool
    {
        if ($formField->isValueEmpty()) {
            return true;
        }
        $value = $formField->getRawValue();
        if (
            preg_match(
                pattern: '/^(\d{1,2})\.(\d{1,2})\.(\d{4})$/',
                subject: $value,
                matches: $matches
            ) === 1
        ) {
            $value = implode(
                separator: '-',
                array: [
                    $matches[3],
                    $matches[2],
                    $matches[1],
                ]
            );
        } else {
            if (
                preg_match(
                    pattern: '/^\d{4}-\d{1,2}-\d{1,2}$/',
                    subject: $value
                ) !== 1
            ) {
                return false;
            }
        }
        try {
            $dateTime = new DateTimeImmutable(datetime: $value);
            if (DateTimeImmutable::getLastErrors() !== false) {
                return false;
            }
            $formField->setValue(value: $dateTime->format(format: 'Y-m-d'));
        } catch (Throwable) {
            return false;
        }

        return true;
    }
}