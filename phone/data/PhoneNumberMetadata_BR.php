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
            'NationalNumberPattern' => '(?:[1-46-9]\\d\\d|5(?:[0-46-9]\\d|5[0-24679]))\\d{8}|[1-9]\\d{9}|[3589]\\d{8}|[34]\\d{7}',
            'PossibleLength' =>
                [
                    0 => 8,
                    1 => 9,
                    2 => 10,
                    3 => 11,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'fixedLine' =>
        [
            'NationalNumberPattern' => '(?:[14689][1-9]|2[12478]|3[1-578]|5[13-5]|7[13-579])[2-5]\\d{7}',
            'ExampleNumber' => '1123456789',
            'PossibleLength' =>
                [
                    0 => 10,
                ],
            'PossibleLengthLocalOnly' =>
                [
                    0 => 8,
                ],
        ],
    'mobile' =>
        [
            'NationalNumberPattern' => '(?:[14689][1-9]|2[12478]|3[1-578]|5[13-5]|7[13-579])(?:7|9\\d)\\d{7}',
            'ExampleNumber' => '11961234567',
            'PossibleLength' =>
                [
                    0 => 10,
                    1 => 11,
                ],
            'PossibleLengthLocalOnly' =>
                [
                    0 => 8,
                    1 => 9,
                ],
        ],
    'tollFree' =>
        [
            'NationalNumberPattern' => '800\\d{6,7}',
            'ExampleNumber' => '800123456',
            'PossibleLength' =>
                [
                    0 => 9,
                    1 => 10,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'premiumRate' =>
        [
            'NationalNumberPattern' => '300\\d{6}|[59]00\\d{6,7}',
            'ExampleNumber' => '300123456',
            'PossibleLength' =>
                [
                    0 => 9,
                    1 => 10,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'sharedCost' =>
        [
            'NationalNumberPattern' => '300\\d{7}|[34]00\\d{5}|4(?:02|37)0\\d{4}',
            'ExampleNumber' => '40041234',
            'PossibleLength' =>
                [
                    0 => 8,
                    1 => 10,
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
            'NationalNumberPattern' => '4020\\d{4}|[34]00\\d{5}',
            'PossibleLength' =>
                [
                    0 => 8,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'id' => 'BR',
    'countryCode' => 55,
    'internationalPrefix' => '00(?:1[245]|2[1-35]|31|4[13]|[56]5|99)',
    'nationalPrefix' => '0',
    'nationalPrefixForParsing' => '0(?:(1[245]|2[1-35]|31|4[13]|[56]5|99)(\\d{10,11}))?',
    'nationalPrefixTransformRule' => '$2',
    'sameMobileAndFixedLinePattern' => false,
    'numberFormat' =>
        [
            0 =>
                [
                    'pattern' => '(\\d{3,6})',
                    'format' => '$1',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '1(?:1[25-8]|2[357-9]|3[02-68]|4[12568]|5|6[0-8]|8[015]|9[0-47-9])|321|610',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            1 =>
                [
                    'pattern' => '(\\d{4})(\\d{4})',
                    'format' => '$1-$2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '300|4(?:0[02]|37)',
                            1 => '4(?:02|37)0|[34]00',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            2 =>
                [
                    'pattern' => '(\\d{4})(\\d{4})',
                    'format' => '$1-$2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[2-57]',
                            1 => '[2357]|4(?:[0-24-9]|3(?:[0-689]|7[1-9]))',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            3 =>
                [
                    'pattern' => '(\\d{3})(\\d{2,3})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '(?:[358]|90)0',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            4 =>
                [
                    'pattern' => '(\\d{5})(\\d{4})',
                    'format' => '$1-$2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '9',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            5 =>
                [
                    'pattern' => '(\\d{2})(\\d{4})(\\d{4})',
                    'format' => '$1 $2-$3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '(?:[14689][1-9]|2[12478]|3[1-578]|5[13-5]|7[13-579])[2-57]',
                        ],
                    'nationalPrefixFormattingRule' => '($1)',
                    'domesticCarrierCodeFormattingRule' => '0 $CC ($1)',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            6 =>
                [
                    'pattern' => '(\\d{2})(\\d{5})(\\d{4})',
                    'format' => '$1 $2-$3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[16][1-9]|[2-57-9]',
                        ],
                    'nationalPrefixFormattingRule' => '($1)',
                    'domesticCarrierCodeFormattingRule' => '0 $CC ($1)',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
        ],
    'intlNumberFormat' =>
        [
            0 =>
                [
                    'pattern' => '(\\d{4})(\\d{4})',
                    'format' => '$1-$2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '300|4(?:0[02]|37)',
                            1 => '4(?:02|37)0|[34]00',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            1 =>
                [
                    'pattern' => '(\\d{3})(\\d{2,3})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '(?:[358]|90)0',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            2 =>
                [
                    'pattern' => '(\\d{2})(\\d{4})(\\d{4})',
                    'format' => '$1 $2-$3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '(?:[14689][1-9]|2[12478]|3[1-578]|5[13-5]|7[13-579])[2-57]',
                        ],
                    'nationalPrefixFormattingRule' => '($1)',
                    'domesticCarrierCodeFormattingRule' => '0 $CC ($1)',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            3 =>
                [
                    'pattern' => '(\\d{2})(\\d{5})(\\d{4})',
                    'format' => '$1 $2-$3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[16][1-9]|[2-57-9]',
                        ],
                    'nationalPrefixFormattingRule' => '($1)',
                    'domesticCarrierCodeFormattingRule' => '0 $CC ($1)',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
        ],
    'mainCountryForCode' => false,
    'leadingZeroPossible' => false,
    'mobileNumberPortableRegion' => true,
];