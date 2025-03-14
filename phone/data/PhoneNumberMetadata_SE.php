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
            'NationalNumberPattern' => '(?:[26]\\d\\d|9)\\d{9}|[1-9]\\d{8}|[1-689]\\d{7}|[1-4689]\\d{6}|2\\d{5}',
            'PossibleLength' =>
                [
                    0 => 6,
                    1 => 7,
                    2 => 8,
                    3 => 9,
                    4 => 10,
                    5 => 12,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'fixedLine' =>
        [
            'NationalNumberPattern' => '10[1-8]\\d{6}|90[1-9]\\d{4,6}|(?:[12][136]|3[356]|4[0246]|6[03]|8\\d)\\d{5,7}|(?:1(?:2[0-35]|4[0-4]|5[0-25-9]|7[13-6]|[89]\\d)|2(?:2[0-7]|4[0136-8]|5[0138]|7[018]|8[01]|9[0-57])|3(?:0[0-4]|1\\d|2[0-25]|4[056]|7[0-2]|8[0-3]|9[023])|4(?:1[013-8]|3[0135]|5[14-79]|7[0-246-9]|8[0156]|9[0-689])|5(?:0[0-6]|[15][0-5]|2[0-68]|3[0-4]|4\\d|6[03-5]|7[013]|8[0-79]|9[01])|6(?:1[1-3]|2[0-4]|4[02-57]|5[0-37]|6[0-3]|7[0-2]|8[0247]|9[0-356])|9(?:1[0-68]|2\\d|3[02-5]|4[0-3]|5[0-4]|[68][01]|7[0135-8]))\\d{5,6}',
            'ExampleNumber' => '8123456',
            'PossibleLength' =>
                [
                    0 => 7,
                    1 => 8,
                    2 => 9,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'mobile' =>
        [
            'NationalNumberPattern' => '7[02369]\\d{7}',
            'ExampleNumber' => '701234567',
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
            'NationalNumberPattern' => '20\\d{4,7}',
            'ExampleNumber' => '20123456',
            'PossibleLength' =>
                [
                    0 => 6,
                    1 => 7,
                    2 => 8,
                    3 => 9,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'premiumRate' =>
        [
            'NationalNumberPattern' => '649\\d{6}|9(?:00|39|44)[1-8]\\d{3,6}',
            'ExampleNumber' => '9001234567',
            'PossibleLength' =>
                [
                    0 => 7,
                    1 => 8,
                    2 => 9,
                    3 => 10,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'sharedCost' =>
        [
            'NationalNumberPattern' => '77[0-7]\\d{6}',
            'ExampleNumber' => '771234567',
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
            'NationalNumberPattern' => '75[1-8]\\d{6}',
            'ExampleNumber' => '751234567',
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
            'NationalNumberPattern' => '74[02-9]\\d{6}',
            'ExampleNumber' => '740123456',
            'PossibleLength' =>
                [
                    0 => 9,
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
            'NationalNumberPattern' => '(?:25[245]|67[3-68])\\d{9}',
            'ExampleNumber' => '254123456789',
            'PossibleLength' =>
                [
                    0 => 12,
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
    'id' => 'SE',
    'countryCode' => 46,
    'internationalPrefix' => '00',
    'nationalPrefix' => '0',
    'nationalPrefixForParsing' => '0',
    'sameMobileAndFixedLinePattern' => false,
    'numberFormat' =>
        [
            0 =>
                [
                    'pattern' => '(\\d{2})(\\d{2,3})(\\d{2})',
                    'format' => '$1-$2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '20',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            1 =>
                [
                    'pattern' => '(\\d{3})(\\d{4})',
                    'format' => '$1-$2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '9(?:00|39|44)',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            2 =>
                [
                    'pattern' => '(\\d{2})(\\d{3})(\\d{2})',
                    'format' => '$1-$2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[12][136]|3[356]|4[0246]|6[03]|90[1-9]',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            3 =>
                [
                    'pattern' => '(\\d)(\\d{2,3})(\\d{2})(\\d{2})',
                    'format' => '$1-$2 $3 $4',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '8',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            4 =>
                [
                    'pattern' => '(\\d{3})(\\d{2,3})(\\d{2})',
                    'format' => '$1-$2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '1[2457]|2(?:[247-9]|5[0138])|3[0247-9]|4[1357-9]|5[0-35-9]|6(?:[125689]|4[02-57]|7[0-2])|9(?:[125-8]|3[02-5]|4[0-3])',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            5 =>
                [
                    'pattern' => '(\\d{3})(\\d{2,3})(\\d{3})',
                    'format' => '$1-$2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '9(?:00|39|44)',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            6 =>
                [
                    'pattern' => '(\\d{2})(\\d{2,3})(\\d{2})(\\d{2})',
                    'format' => '$1-$2 $3 $4',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '1[013689]|2[0136]|3[1356]|4[0246]|54|6[03]|90[1-9]',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            7 =>
                [
                    'pattern' => '(\\d{2})(\\d{3})(\\d{2})(\\d{2})',
                    'format' => '$1-$2 $3 $4',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '7',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            8 =>
                [
                    'pattern' => '(\\d)(\\d{3})(\\d{3})(\\d{2})',
                    'format' => '$1-$2 $3 $4',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '8',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            9 =>
                [
                    'pattern' => '(\\d{3})(\\d{2})(\\d{2})(\\d{2})',
                    'format' => '$1-$2 $3 $4',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[13-5]|2(?:[247-9]|5[0138])|6(?:[124-689]|7[0-2])|9(?:[125-8]|3[02-5]|4[0-3])',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            10 =>
                [
                    'pattern' => '(\\d{3})(\\d{2})(\\d{2})(\\d{3})',
                    'format' => '$1-$2 $3 $4',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '9',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            11 =>
                [
                    'pattern' => '(\\d{3})(\\d{2})(\\d{3})(\\d{2})(\\d{2})',
                    'format' => '$1-$2 $3 $4 $5',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[26]',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
        ],
    'intlNumberFormat' =>
        [
            0 =>
                [
                    'pattern' => '(\\d{2})(\\d{2,3})(\\d{2})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '20',
                        ],
                ],
            1 =>
                [
                    'pattern' => '(\\d{3})(\\d{4})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '9(?:00|39|44)',
                        ],
                ],
            2 =>
                [
                    'pattern' => '(\\d{2})(\\d{3})(\\d{2})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[12][136]|3[356]|4[0246]|6[03]|90[1-9]',
                        ],
                ],
            3 =>
                [
                    'pattern' => '(\\d)(\\d{2,3})(\\d{2})(\\d{2})',
                    'format' => '$1 $2 $3 $4',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '8',
                        ],
                ],
            4 =>
                [
                    'pattern' => '(\\d{3})(\\d{2,3})(\\d{2})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '1[2457]|2(?:[247-9]|5[0138])|3[0247-9]|4[1357-9]|5[0-35-9]|6(?:[125689]|4[02-57]|7[0-2])|9(?:[125-8]|3[02-5]|4[0-3])',
                        ],
                ],
            5 =>
                [
                    'pattern' => '(\\d{3})(\\d{2,3})(\\d{3})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '9(?:00|39|44)',
                        ],
                ],
            6 =>
                [
                    'pattern' => '(\\d{2})(\\d{2,3})(\\d{2})(\\d{2})',
                    'format' => '$1 $2 $3 $4',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '1[013689]|2[0136]|3[1356]|4[0246]|54|6[03]|90[1-9]',
                        ],
                ],
            7 =>
                [
                    'pattern' => '(\\d{2})(\\d{3})(\\d{2})(\\d{2})',
                    'format' => '$1 $2 $3 $4',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '7',
                        ],
                ],
            8 =>
                [
                    'pattern' => '(\\d)(\\d{3})(\\d{3})(\\d{2})',
                    'format' => '$1 $2 $3 $4',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '8',
                        ],
                ],
            9 =>
                [
                    'pattern' => '(\\d{3})(\\d{2})(\\d{2})(\\d{2})',
                    'format' => '$1 $2 $3 $4',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[13-5]|2(?:[247-9]|5[0138])|6(?:[124-689]|7[0-2])|9(?:[125-8]|3[02-5]|4[0-3])',
                        ],
                ],
            10 =>
                [
                    'pattern' => '(\\d{3})(\\d{2})(\\d{2})(\\d{3})',
                    'format' => '$1 $2 $3 $4',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '9',
                        ],
                ],
            11 =>
                [
                    'pattern' => '(\\d{3})(\\d{2})(\\d{3})(\\d{2})(\\d{2})',
                    'format' => '$1 $2 $3 $4 $5',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[26]',
                        ],
                ],
        ],
    'mainCountryForCode' => false,
    'leadingZeroPossible' => false,
    'mobileNumberPortableRegion' => true,
];