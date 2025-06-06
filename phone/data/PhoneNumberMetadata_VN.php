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
            'NationalNumberPattern' => '[12]\\d{9}|[135-9]\\d{8}|[16]\\d{7}|[16-8]\\d{6}',
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
    'fixedLine' =>
        [
            'NationalNumberPattern' => '2(?:0[3-9]|1[0-689]|2[0-25-9]|3[2-9]|4[2-8]|5[124-9]|6[0-39]|7[0-7]|8[2-79]|9[0-4679])\\d{7}',
            'ExampleNumber' => '2101234567',
            'PossibleLength' =>
                [
                    0 => 10,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'mobile' =>
        [
            'NationalNumberPattern' => '(?:52[238]|8(?:79|9[689])|99[013-9])\\d{6}|(?:3\\d|5[689]|7[06-9]|8[1-68]|9[0-8])\\d{7}',
            'ExampleNumber' => '912345678',
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
            'NationalNumberPattern' => '1800\\d{4,6}|12(?:03|28)\\d{4}',
            'ExampleNumber' => '1800123456',
            'PossibleLength' =>
                [
                    0 => 8,
                    1 => 9,
                    2 => 10,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'premiumRate' =>
        [
            'NationalNumberPattern' => '1900\\d{4,6}',
            'ExampleNumber' => '1900123456',
            'PossibleLength' =>
                [
                    0 => 8,
                    1 => 9,
                    2 => 10,
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
            'NationalNumberPattern' => '672\\d{6}',
            'ExampleNumber' => '672012345',
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
            'NationalNumberPattern' => '(?:[17]99|80\\d)\\d{4}|69\\d{5,6}',
            'ExampleNumber' => '1992000',
            'PossibleLength' =>
                [
                    0 => 7,
                    1 => 8,
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
            'NationalNumberPattern' => '[17]99\\d{4}|69\\d{5,6}',
            'PossibleLength' =>
                [
                    0 => 7,
                    1 => 8,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'id' => 'VN',
    'countryCode' => 84,
    'internationalPrefix' => '00',
    'nationalPrefix' => '0',
    'nationalPrefixForParsing' => '0',
    'sameMobileAndFixedLinePattern' => false,
    'numberFormat' =>
        [
            0 =>
                [
                    'pattern' => '(\\d{3})(\\d{4})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[17]99',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            1 =>
                [
                    'pattern' => '(\\d{2})(\\d{5})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '80',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            2 =>
                [
                    'pattern' => '(\\d{3})(\\d{4,5})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '69',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            3 =>
                [
                    'pattern' => '(\\d{4})(\\d{4,6})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '1',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            4 =>
                [
                    'pattern' => '(\\d{2})(\\d{3})(\\d{2})(\\d{2})',
                    'format' => '$1 $2 $3 $4',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[69]',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            5 =>
                [
                    'pattern' => '(\\d{3})(\\d{3})(\\d{3})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[3578]',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            6 =>
                [
                    'pattern' => '(\\d{2})(\\d{4})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '2[48]',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            7 =>
                [
                    'pattern' => '(\\d{3})(\\d{4})(\\d{3})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '2',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
        ],
    'intlNumberFormat' =>
        [
            0 =>
                [
                    'pattern' => '(\\d{2})(\\d{5})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '80',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            1 =>
                [
                    'pattern' => '(\\d{4})(\\d{4,6})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '1',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            2 =>
                [
                    'pattern' => '(\\d{2})(\\d{3})(\\d{2})(\\d{2})',
                    'format' => '$1 $2 $3 $4',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[69]',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            3 =>
                [
                    'pattern' => '(\\d{3})(\\d{3})(\\d{3})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[3578]',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            4 =>
                [
                    'pattern' => '(\\d{2})(\\d{4})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '2[48]',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            5 =>
                [
                    'pattern' => '(\\d{3})(\\d{4})(\\d{3})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '2',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
        ],
    'mainCountryForCode' => false,
    'leadingZeroPossible' => false,
    'mobileNumberPortableRegion' => false,
];