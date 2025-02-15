<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\datacheck\validatorTypes;

use framework\datacheck\Validator;

class DomainValidator
{
    public static function validate(string $input): bool
    {
        if (!Validator::stringWithoutWhitespaces(input: $input)) {
            return false;
        }
        // Domainname + '.' + TLD = minimum 5 characters
        if (mb_strlen(string: $input) < 5) {
            return false;
        }
        $pieces = explode(
            separator: '.',
            string: $input
        );
        if ($pieces < 2) {
            return false;
        }
        $realTld = array_pop(array: $pieces);
        if (!TldValidator::validate(input: $realTld)) {
            return false;
        }
        $encodedData = idn_to_ascii(domain: $input);
        if ($encodedData === false) {
            return false;
        }
        if (filter_var(value: $encodedData, filter: FILTER_VALIDATE_DOMAIN, options: FILTER_FLAG_HOSTNAME) === false) {
            return false;
        }

        return true;
    }
}