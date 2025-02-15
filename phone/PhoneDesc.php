<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * .
 * Adapted work based on https://github.com/giggsey/libphonenumber-for-php , which was published
 * with "Apache License Version 2.0, January 2004" ( http://www.apache.org/licenses/ )
 */

namespace framework\phone;

class PhoneDesc
{
    private(set) string $nationalNumberPattern = '';
    private(set) array $possibleLength;
    private(set) array $possibleLengthLocalOnly;

    public function __construct(array $input)
    {
        if (array_key_exists(key: 'NationalNumberPattern', array: $input) && trim(
                string: $input['NationalNumberPattern']
            ) !== '') {
            $this->nationalNumberPattern = $input['NationalNumberPattern'];
        }
        $this->possibleLength = $input['PossibleLength'];
        $this->possibleLengthLocalOnly = $input['PossibleLengthLocalOnly'];
    }
}