<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * .
 * Adapted work based on https://github.com/giggsey/libphonenumber-for-php , which was published
 * with "Apache License Version 2.0, January 2004" ( http://www.apache.org/licenses/ )
 */

use framework\phone\PhoneCountryCodes;

return [
    'generalDesc' =>
        [
            'NationalNumberPattern' => '0\\d{5,10}|3[0-8]\\d{7,10}|55\\d{8}|8\\d{5}(?:\\d{2,4})?|(?:1\\d|39)\\d{7,8}',
            'PossibleLength' =>
                [
                    0 => 6,
                    1 => 7,
                    2 => 8,
                    3 => 9,
                    4 => 10,
                    5 => 11,
                    6 => 12,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'fixedLine' =>
        [
            'NationalNumberPattern' => '0669[0-79]\\d{1,6}|0(?:1(?:[0159]\\d|[27][1-5]|31|4[1-4]|6[1356]|8[2-57])|2\\d\\d|3(?:[0159]\\d|2[1-4]|3[12]|[48][1-6]|6[2-59]|7[1-7])|4(?:[0159]\\d|[23][1-9]|4[245]|6[1-5]|7[1-4]|81)|5(?:[0159]\\d|2[1-5]|3[2-6]|4[1-79]|6[4-6]|7[1-578]|8[3-8])|6(?:[0-57-9]\\d|6[0-8])|7(?:[0159]\\d|2[12]|3[1-7]|4[2-46]|6[13569]|7[13-6]|8[1-59])|8(?:[0159]\\d|2[3-578]|3[1-356]|[6-8][1-5])|9(?:[0159]\\d|[238][1-5]|4[12]|6[1-8]|7[1-6]))\\d{2,7}',
            'ExampleNumber' => '0212345678',
            'PossibleLength' =>
                [
                    0 => 6,
                    1 => 7,
                    2 => 8,
                    3 => 9,
                    4 => 10,
                    5 => 11,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'mobile' =>
        [
            'NationalNumberPattern' => '3[1-9]\\d{8}|3[2-9]\\d{7}',
            'ExampleNumber' => '3123456789',
            'PossibleLength' =>
                [
                    0 => 9,
                    1 => 10,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'tollFree' =>
        [
            'NationalNumberPattern' => '80(?:0\\d{3}|3)\\d{3}',
            'ExampleNumber' => '800123456',
            'PossibleLength' =>
                [
                    0 => 6,
                    1 => 9,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'premiumRate' =>
        [
            'NationalNumberPattern' => '(?:0878\\d\\d|89(?:2|4[5-9]\\d))\\d{3}|89[45][0-4]\\d\\d|(?:1(?:44|6[346])|89(?:5[5-9]|9))\\d{6}',
            'ExampleNumber' => '899123456',
            'PossibleLength' =>
                [
                    0 => 6,
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
            'NationalNumberPattern' => '84(?:[08]\\d{3}|[17])\\d{3}',
            'ExampleNumber' => '848123456',
            'PossibleLength' =>
                [
                    0 => 6,
                    1 => 9,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'personalNumber' =>
        [
            'NationalNumberPattern' => '1(?:78\\d|99)\\d{6}',
            'ExampleNumber' => '1781234567',
            'PossibleLength' =>
                [
                    0 => 9,
                    1 => 10,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'voip' =>
        [
            'NationalNumberPattern' => '55\\d{8}',
            'ExampleNumber' => '5512345678',
            'PossibleLength' =>
                [
                    0 => 10,
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
            'NationalNumberPattern' => '3[2-8]\\d{9,10}',
            'ExampleNumber' => '33101234501',
            'PossibleLength' =>
                [
                    0 => 11,
                    1 => 12,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'noInternationalDialling' =>
        [
            'NationalNumberPattern' => '848\\d{6}',
            'PossibleLength' =>
                [
                    0 => 9,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'id' => 'IT',
    'countryCode' => PhoneCountryCodes::IT,
    'internationalPrefix' => '00',
    'sameMobileAndFixedLinePattern' => false,
    'numberFormat' =>
        [
            0 =>
                [
                    'pattern' => '(\\d{4,5})',
                    'format' => '$1',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '1(?:0|9[246])',
                            1 => '1(?:0|9(?:2[2-9]|[46]))',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            1 =>
                [
                    'pattern' => '(\\d{6})',
                    'format' => '$1',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '1(?:1|92)',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            2 =>
                [
                    'pattern' => '(\\d{2})(\\d{4,6})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '0[26]',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            3 =>
                [
                    'pattern' => '(\\d{3})(\\d{3,6})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '0[13-57-9][0159]|8(?:03|4[17]|9[245])',
                            1 => '0[13-57-9][0159]|8(?:03|4[17]|9(?:2|[45][0-4]))',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            4 =>
                [
                    'pattern' => '(\\d{4})(\\d{2,6})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '0(?:[13-579][2-46-8]|8[236-8])',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            5 =>
                [
                    'pattern' => '(\\d{4})(\\d{4})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '894',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            6 =>
                [
                    'pattern' => '(\\d{2})(\\d{3,4})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '0[26]|5',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            7 =>
                [
                    'pattern' => '(\\d{3})(\\d{3})(\\d{3,4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '1[4679]|[38]',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            8 =>
                [
                    'pattern' => '(\\d{3})(\\d{3,4})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '0[13-57-9][0159]',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            9 =>
                [
                    'pattern' => '(\\d{2})(\\d{4})(\\d{5})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '0[26]',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            10 =>
                [
                    'pattern' => '(\\d{4})(\\d{3})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '0',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            11 =>
                [
                    'pattern' => '(\\d{3})(\\d{4})(\\d{4,5})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '3',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
        ],
    'intlNumberFormat' =>
        [
            0 =>
                [
                    'pattern' => '(\\d{2})(\\d{4,6})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '0[26]',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            1 =>
                [
                    'pattern' => '(\\d{3})(\\d{3,6})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '0[13-57-9][0159]|8(?:03|4[17]|9[245])',
                            1 => '0[13-57-9][0159]|8(?:03|4[17]|9(?:2|[45][0-4]))',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            2 =>
                [
                    'pattern' => '(\\d{4})(\\d{2,6})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '0(?:[13-579][2-46-8]|8[236-8])',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            3 =>
                [
                    'pattern' => '(\\d{4})(\\d{4})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '894',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            4 =>
                [
                    'pattern' => '(\\d{2})(\\d{3,4})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '0[26]|5',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            5 =>
                [
                    'pattern' => '(\\d{3})(\\d{3})(\\d{3,4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '1[4679]|[38]',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            6 =>
                [
                    'pattern' => '(\\d{3})(\\d{3,4})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '0[13-57-9][0159]',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            7 =>
                [
                    'pattern' => '(\\d{2})(\\d{4})(\\d{5})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '0[26]',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            8 =>
                [
                    'pattern' => '(\\d{4})(\\d{3})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '0',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            9 =>
                [
                    'pattern' => '(\\d{3})(\\d{4})(\\d{4,5})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '3',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
        ],
    'mainCountryForCode' => true,
    'leadingZeroPossible' => false,
    'mobileNumberPortableRegion' => true,
];