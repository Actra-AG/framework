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
            'NationalNumberPattern' => '(?:[2-578]\\d|60)\\d{7}|9\\d{8,11}',
            'PossibleLength' =>
                [
                    0 => 9,
                    1 => 10,
                    2 => 11,
                    3 => 12,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'fixedLine' =>
        [
            'NationalNumberPattern' => '(?:2\\d|3[1257-9]|4[16-9]|5[13-9])\\d{7}',
            'ExampleNumber' => '212345678',
            'PossibleLength' =>
                [
                    0 => 9,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'mobile' =>
        [
            'NationalNumberPattern' => '(?:60[1-8]|7(?:0[2-5]|[2379]\\d))\\d{6}',
            'ExampleNumber' => '601123456',
            'PossibleLength' =>
                [
                    0 => 9,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'tollFree' =>
        [
            'NationalNumberPattern' => '800\\d{6}',
            'ExampleNumber' => '800123456',
            'PossibleLength' =>
                [
                    0 => 9,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'premiumRate' =>
        [
            'NationalNumberPattern' => '9(?:0[05689]|76)\\d{6}',
            'ExampleNumber' => '900123456',
            'PossibleLength' =>
                [
                    0 => 9,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'sharedCost' =>
        [
            'NationalNumberPattern' => '8[134]\\d{7}',
            'ExampleNumber' => '811234567',
            'PossibleLength' =>
                [
                    0 => 9,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'personalNumber' =>
        [
            'NationalNumberPattern' => '70[01]\\d{6}',
            'ExampleNumber' => '700123456',
            'PossibleLength' =>
                [
                    0 => 9,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'voip' =>
        [
            'NationalNumberPattern' => '9[17]0\\d{6}',
            'ExampleNumber' => '910123456',
            'PossibleLength' =>
                [
                    0 => 9,
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
            'NationalNumberPattern' => '9(?:5\\d|7[2-4])\\d{6}',
            'ExampleNumber' => '972123456',
            'PossibleLength' =>
                [
                    0 => 9,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'voicemail' =>
        [
            'NationalNumberPattern' => '9(?:3\\d{9}|6\\d{7,10})',
            'ExampleNumber' => '93123456789',
            'PossibleLength' =>
                [
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
    'id' => 'CZ',
    'countryCode' => 420,
    'internationalPrefix' => '00',
    'sameMobileAndFixedLinePattern' => false,
    'numberFormat' =>
        [
            0 =>
                [
                    'pattern' => '(\\d{3})(\\d{3})(\\d{3})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[2-8]|9[015-7]',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            1 =>
                [
                    'pattern' => '(\\d{2})(\\d{3})(\\d{3})(\\d{3})',
                    'format' => '$1 $2 $3 $4',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '9',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            2 =>
                [
                    'pattern' => '(\\d{3})(\\d{3})(\\d{3})(\\d{3})',
                    'format' => '$1 $2 $3 $4',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '9',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
        ],
    'intlNumberFormat' =>
        [
        ],
    'mainCountryForCode' => false,
    'leadingZeroPossible' => false,
    'mobileNumberPortableRegion' => true,
];