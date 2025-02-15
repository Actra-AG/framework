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
            'NationalNumberPattern' => '(?:00|[3-59]\\d|77|88)\\d{7}',
            'PossibleLength' =>
                [
                    0 => 9,
                ],
            'PossibleLengthLocalOnly' =>
                [
                    0 => 3,
                    1 => 5,
                    2 => 6,
                    3 => 7,
                ],
        ],
    'fixedLine' =>
        [
            'NationalNumberPattern' => '(?:3(?:1[3-5]|2[245]|3[12]|4[24-7]|5[25]|72)|4(?:46|74|87))\\d{6}',
            'ExampleNumber' => '372123456',
            'PossibleLength' =>
                [
                ],
            'PossibleLengthLocalOnly' =>
                [
                    0 => 3,
                    1 => 5,
                    2 => 6,
                    3 => 7,
                ],
        ],
    'mobile' =>
        [
            'NationalNumberPattern' => '41[18]\\d{6}|(?:00|5[05]|77|88|9\\d)\\d{7}',
            'ExampleNumber' => '917123456',
            'PossibleLength' =>
                [
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'tollFree' =>
        [
            'PossibleLength' =>
                [
                    0 => -1,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'premiumRate' =>
        [
            'PossibleLength' =>
                [
                    0 => -1,
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
            'PossibleLength' =>
                [
                    0 => -1,
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
    'id' => 'TJ',
    'countryCode' => 992,
    'internationalPrefix' => '810',
    'preferredInternationalPrefix' => '8~10',
    'nationalPrefix' => '8',
    'nationalPrefixForParsing' => '8',
    'sameMobileAndFixedLinePattern' => false,
    'numberFormat' =>
        [
            0 =>
                [
                    'pattern' => '(\\d{6})(\\d)(\\d{2})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '331',
                            1 => '3317',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            1 =>
                [
                    'pattern' => '(\\d{3})(\\d{2})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[34]7|91[78]',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            2 =>
                [
                    'pattern' => '(\\d{4})(\\d)(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '3',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            3 =>
                [
                    'pattern' => '(\\d{2})(\\d{3})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[0457-9]',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
        ],
    'intlNumberFormat' =>
        [
        ],
    'mainCountryForCode' => false,
    'leadingZeroPossible' => false,
    'mobileNumberPortableRegion' => false,
];