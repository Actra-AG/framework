<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * .
 * Adapted work based on https://github.com/giggsey/libphonenumber-for-php , which was published
 * with "Apache License Version 2.0, January 2004" ( http://www.apache.org/licenses/ )
 */

return [
    'generalDesc' =>
        [
            'NationalNumberPattern' => '(?:[58]\\d\\d|784|900)\\d{7}',
            'PossibleLength' =>
                [
                    0 => 10,
                ],
            'PossibleLengthLocalOnly' =>
                [
                    0 => 7,
                ],
        ],
    'fixedLine' =>
        [
            'NationalNumberPattern' => '784(?:266|3(?:6[6-9]|7\\d|8[0-24-6])|4(?:38|5[0-36-8]|8[0-8])|5(?:55|7[0-2]|93)|638|784)\\d{4}',
            'ExampleNumber' => '7842661234',
            'PossibleLength' =>
                [
                ],
            'PossibleLengthLocalOnly' =>
                [
                    0 => 7,
                ],
        ],
    'mobile' =>
        [
            'NationalNumberPattern' => '784(?:4(?:3[0-5]|5[45]|89|9[0-8])|5(?:2[6-9]|3[0-4]))\\d{4}',
            'ExampleNumber' => '7844301234',
            'PossibleLength' =>
                [
                ],
            'PossibleLengthLocalOnly' =>
                [
                    0 => 7,
                ],
        ],
    'tollFree' =>
        [
            'NationalNumberPattern' => '8(?:00|33|44|55|66|77|88)[2-9]\\d{6}',
            'ExampleNumber' => '8002345678',
            'PossibleLength' =>
                [
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'premiumRate' =>
        [
            'NationalNumberPattern' => '900[2-9]\\d{6}',
            'ExampleNumber' => '9002345678',
            'PossibleLength' =>
                [
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'sharedCost' =>
        [
            'PossibleLength' =>
                [
                    0 => -1,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'personalNumber' =>
        [
            'NationalNumberPattern' => '5(?:00|2[12]|33|44|66|77|88)[2-9]\\d{6}',
            'ExampleNumber' => '5002345678',
            'PossibleLength' =>
                [
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'voip' =>
        [
            'PossibleLength' =>
                [
                    0 => -1,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'pager' =>
        [
            'PossibleLength' =>
                [
                    0 => -1,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'uan' =>
        [
            'PossibleLength' =>
                [
                    0 => -1,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'voicemail' =>
        [
            'PossibleLength' =>
                [
                    0 => -1,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'noInternationalDialling' =>
        [
            'PossibleLength' =>
                [
                    0 => -1,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'id' => 'VC',
    'countryCode' => 1,
    'internationalPrefix' => '011',
    'nationalPrefix' => '1',
    'nationalPrefixForParsing' => '1|([2-7]\\d{6})$',
    'nationalPrefixTransformRule' => '784$1',
    'sameMobileAndFixedLinePattern' => false,
    'numberFormat' =>
        [
        ],
    'intlNumberFormat' =>
        [
        ],
    'mainCountryForCode' => false,
    'leadingDigits' => '784',
    'leadingZeroPossible' => false,
    'mobileNumberPortableRegion' => false,
];