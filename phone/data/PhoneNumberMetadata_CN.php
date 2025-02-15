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
            'NationalNumberPattern' => '1[1279]\\d{8,9}|2\\d{9}(?:\\d{2})?|[12]\\d{6,7}|86\\d{6}|(?:1[03-68]\\d|6)\\d{7,9}|(?:[3-579]\\d|8[0-57-9])\\d{6,9}',
            'PossibleLength' =>
                [
                    0 => 7,
                    1 => 8,
                    2 => 9,
                    3 => 10,
                    4 => 11,
                    5 => 12,
                ],
            'PossibleLengthLocalOnly' =>
                [
                    0 => 5,
                    1 => 6,
                ],
        ],
    'fixedLine' =>
        [
            'NationalNumberPattern' => '(?:10(?:[02-79]\\d\\d|[18](?:0[1-9]|[1-9]\\d))|21(?:[18](?:0[1-9]|[1-9]\\d)|[2-79]\\d\\d))\\d{5}|(?:43[35]|754)\\d{7,8}|8(?:078\\d{7}|51\\d{7,8})|(?:10|(?:2|85)1|43[35]|754)(?:100\\d\\d|95\\d{3,4})|(?:2[02-57-9]|3(?:11|7[179])|4(?:[15]1|3[12])|5(?:1\\d|2[37]|3[12]|51|7[13-79]|9[15])|7(?:[39]1|5[57]|6[09])|8(?:71|98))(?:[02-8]\\d{7}|1(?:0(?:0\\d\\d(?:\\d{3})?|[1-9]\\d{5})|[1-9]\\d{6})|9(?:[0-46-9]\\d{6}|5\\d{3}(?:\\d(?:\\d{2})?)?))|(?:3(?:1[02-9]|35|49|5\\d|7[02-68]|9[1-68])|4(?:1[02-9]|2[179]|3[46-9]|5[2-9]|6[47-9]|7\\d|8[23])|5(?:3[03-9]|4[36]|5[02-9]|6[1-46]|7[028]|80|9[2-46-9])|6(?:3[1-5]|6[0238]|9[12])|7(?:01|[17]\\d|2[248]|3[04-9]|4[3-6]|5[0-3689]|6[2368]|9[02-9])|8(?:1[236-8]|2[5-7]|3\\d|5[2-9]|7[02-9]|8[36-8]|9[1-7])|9(?:0[1-3689]|1[1-79]|[379]\\d|4[13]|5[1-5]))(?:[02-8]\\d{6}|1(?:0(?:0\\d\\d(?:\\d{2})?|[1-9]\\d{4})|[1-9]\\d{5})|9(?:[0-46-9]\\d{5}|5\\d{3,5}))',
            'ExampleNumber' => '1012345678',
            'PossibleLength' =>
                [
                    0 => 7,
                    1 => 8,
                    2 => 9,
                    3 => 10,
                    4 => 11,
                ],
            'PossibleLengthLocalOnly' =>
                [
                    0 => 5,
                    1 => 6,
                ],
        ],
    'mobile' =>
        [
            'NationalNumberPattern' => '1740[0-5]\\d{6}|1(?:[38]\\d|4[57]|5[0-35-9]|6[25-7]|7[0-35-8]|9[189])\\d{8}',
            'ExampleNumber' => '13123456789',
            'PossibleLength' =>
                [
                    0 => 11,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'tollFree' =>
        [
            'NationalNumberPattern' => '(?:(?:10|21)8|8)00\\d{7}',
            'ExampleNumber' => '8001234567',
            'PossibleLength' =>
                [
                    0 => 10,
                    1 => 12,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'premiumRate' =>
        [
            'NationalNumberPattern' => '16[08]\\d{5}',
            'ExampleNumber' => '16812345',
            'PossibleLength' =>
                [
                    0 => 8,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'sharedCost' =>
        [
            'NationalNumberPattern' => '400\\d{7}|950\\d{7,8}|(?:10|2[0-57-9]|3(?:[157]\\d|35|49|9[1-68])|4(?:[17]\\d|2[179]|[35][1-9]|6[47-9]|8[23])|5(?:[1357]\\d|2[37]|4[36]|6[1-46]|80|9[1-9])|6(?:3[1-5]|6[0238]|9[12])|7(?:01|[1579]\\d|2[248]|3[014-9]|4[3-6]|6[023689])|8(?:1[236-8]|2[5-7]|[37]\\d|5[14-9]|8[36-8]|9[1-8])|9(?:0[1-3689]|1[1-79]|[379]\\d|4[13]|5[1-5]))96\\d{3,4}',
            'ExampleNumber' => '4001234567',
            'PossibleLength' =>
                [
                    0 => 7,
                    1 => 8,
                    2 => 9,
                    3 => 10,
                    4 => 11,
                ],
            'PossibleLengthLocalOnly' =>
                [
                    0 => 5,
                    1 => 6,
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
            'NationalNumberPattern' => '(?:(?:10|21)8|[48])00\\d{7}|950\\d{7,8}',
            'PossibleLength' =>
                [
                    0 => 10,
                    1 => 11,
                    2 => 12,
                ],
            'PossibleLengthLocalOnly' =>
                [
                ],
        ],
    'id' => 'CN',
    'countryCode' => 86,
    'internationalPrefix' => '00|1(?:[12]\\d|79|9[0235-7])\\d\\d00',
    'preferredInternationalPrefix' => '00',
    'nationalPrefix' => '0',
    'nationalPrefixForParsing' => '0|(1(?:[12]\\d|79|9[0235-7])\\d\\d)',
    'sameMobileAndFixedLinePattern' => false,
    'numberFormat' =>
        [
            0 =>
                [
                    'pattern' => '(\\d{5,6})',
                    'format' => '$1',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '96',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            1 =>
                [
                    'pattern' => '(\\d{2})(\\d{5,6})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '(?:10|2[0-57-9])[19]',
                            1 => '(?:10|2[0-57-9])(?:10|9[56])',
                            2 => '(?:10|2[0-57-9])(?:100|9[56])',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '$CC $1',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            2 =>
                [
                    'pattern' => '(\\d{3})(\\d{4})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[1-9]',
                            1 => '1[1-9]|26|[3-9]|(?:10|2[0-57-9])(?:[0-8]|9[0-47-9])',
                            2 => '1[1-9]|26|[3-9]|(?:10|2[0-57-9])(?:[02-8]|1(?:0[1-9]|[1-9])|9[0-47-9])',
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
                            0 => '16[08]',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            4 =>
                [
                    'pattern' => '(\\d{3})(\\d{5,6})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '3(?:[157]|35|49|9[1-68])|4(?:[17]|2[179]|6[47-9]|8[23])|5(?:[1357]|2[37]|4[36]|6[1-46]|80)|6(?:3[1-5]|6[0238]|9[12])|7(?:01|[1579]|2[248]|3[014-9]|4[3-6]|6[023689])|8(?:1[236-8]|2[5-7]|[37]|8[36-8]|9[1-8])|9(?:0[1-3689]|1[1-79]|[379]|4[13]|5[1-5])|(?:4[35]|59|85)[1-9]',
                            1 => '(?:3(?:[157]\\d|35|49|9[1-68])|4(?:[17]\\d|2[179]|[35][1-9]|6[47-9]|8[23])|5(?:[1357]\\d|2[37]|4[36]|6[1-46]|80|9[1-9])|6(?:3[1-5]|6[0238]|9[12])|7(?:01|[1579]\\d|2[248]|3[014-9]|4[3-6]|6[023689])|8(?:1[236-8]|2[5-7]|[37]\\d|5[1-9]|8[36-8]|9[1-8])|9(?:0[1-3689]|1[1-79]|[379]\\d|4[13]|5[1-5]))[19]',
                            2 => '85[23](?:10|95)|(?:3(?:[157]\\d|35|49|9[1-68])|4(?:[17]\\d|2[179]|[35][1-9]|6[47-9]|8[23])|5(?:[1357]\\d|2[37]|4[36]|6[1-46]|80|9[1-9])|6(?:3[1-5]|6[0238]|9[12])|7(?:01|[1579]\\d|2[248]|3[014-9]|4[3-6]|6[023689])|8(?:1[236-8]|2[5-7]|[37]\\d|5[14-9]|8[36-8]|9[1-8])|9(?:0[1-3689]|1[1-79]|[379]\\d|4[13]|5[1-5]))(?:10|9[56])',
                            3 => '85[23](?:100|95)|(?:3(?:[157]\\d|35|49|9[1-68])|4(?:[17]\\d|2[179]|[35][1-9]|6[47-9]|8[23])|5(?:[1357]\\d|2[37]|4[36]|6[1-46]|80|9[1-9])|6(?:3[1-5]|6[0238]|9[12])|7(?:01|[1579]\\d|2[248]|3[014-9]|4[3-6]|6[023689])|8(?:1[236-8]|2[5-7]|[37]\\d|5[14-9]|8[36-8]|9[1-8])|9(?:0[1-3689]|1[1-79]|[379]\\d|4[13]|5[1-5]))(?:100|9[56])',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '$CC $1',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            5 =>
                [
                    'pattern' => '(\\d{4})(\\d{4})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[1-9]',
                            1 => '1[1-9]|26|[3-9]|(?:10|2[0-57-9])(?:[0-8]|9[0-47-9])',
                            2 => '26|3(?:[0268]|9[079])|4(?:[049]|2[02-68]|[35]0|6[0-356]|8[014-9])|5(?:0|2[0-24-689]|4[0-2457-9]|6[057-9]|90)|6(?:[0-24578]|6[14-79]|9[03-9])|7(?:0[02-9]|2[0135-79]|3[23]|4[0-27-9]|6[1457]|8)|8(?:[046]|1[01459]|2[0-489]|50|8[0-2459]|9[09])|9(?:0[0457]|1[08]|[268]|4[024-9])|(?:34|85[23])[0-8]|(?:1|58)[1-9]|(?:63|95)[06-9]|(?:33|85[23]9)[0-46-9]|(?:10|2[0-57-9]|3(?:[157]\\d|35|49|9[1-68])|4(?:[17]\\d|2[179]|[35][1-9]|6[47-9]|8[23])|5(?:[1357]\\d|2[37]|4[36]|6[1-46]|80|9[1-9])|6(?:3[1-5]|6[0238]|9[12])|7(?:01|[1579]\\d|2[248]|3[014-9]|4[3-6]|6[023689])|8(?:1[236-8]|2[5-7]|[37]\\d|5[14-9]|8[36-8]|9[1-8])|9(?:0[1-3689]|1[1-79]|[379]\\d|4[13]|5[1-5]))(?:[0-8]|9[0-47-9])',
                            3 => '26|3(?:[0268]|3[0-46-9]|4[0-8]|9[079])|4(?:[049]|2[02-68]|[35]0|6[0-356]|8[014-9])|5(?:0|2[0-24-689]|4[0-2457-9]|6[057-9]|90)|6(?:[0-24578]|3[06-9]|6[14-79]|9[03-9])|7(?:0[02-9]|2[0135-79]|3[23]|4[0-27-9]|6[1457]|8)|8(?:[046]|1[01459]|2[0-489]|5(?:0|[23](?:[02-8]|1[1-9]|9[0-46-9]))|8[0-2459]|9[09])|9(?:0[0457]|1[08]|[268]|4[024-9]|5[06-9])|(?:1|58|85[23]10)[1-9]|(?:10|2[0-57-9])(?:[0-8]|9[0-47-9])|(?:3(?:[157]\\d|35|49|9[1-68])|4(?:[17]\\d|2[179]|[35][1-9]|6[47-9]|8[23])|5(?:[1357]\\d|2[37]|4[36]|6[1-46]|80|9[1-9])|6(?:3[1-5]|6[0238]|9[12])|7(?:01|[1579]\\d|2[248]|3[014-9]|4[3-6]|6[023689])|8(?:1[236-8]|2[5-7]|[37]\\d|5[14-9]|8[36-8]|9[1-8])|9(?:0[1-3689]|1[1-79]|[379]\\d|4[13]|5[1-5]))(?:[02-8]|1(?:0[1-9]|[1-9])|9[0-47-9])',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            6 =>
                [
                    'pattern' => '(\\d{3})(\\d{3})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '(?:4|80)0',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            7 =>
                [
                    'pattern' => '(\\d{2})(\\d{4})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '10|2(?:[02-57-9]|1[1-9])',
                            1 => '10|2(?:[02-57-9]|1[1-9])',
                            2 => '10[0-79]|2(?:[02-57-9]|1[1-79])|(?:10|21)8(?:0[1-9]|[1-9])',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '$CC $1',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            8 =>
                [
                    'pattern' => '(\\d{3})(\\d{3})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '3(?:[3-59]|7[02-68])|4(?:[26-8]|3[3-9]|5[2-9])|5(?:3[03-9]|[468]|7[028]|9[2-46-9])|6|7(?:[0-247]|3[04-9]|5[0-4689]|6[2368])|8(?:[1-358]|9[1-7])|9(?:[013479]|5[1-5])|(?:[34]1|55|79|87)[02-9]',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '$CC $1',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            9 =>
                [
                    'pattern' => '(\\d{3})(\\d{7,8})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '9',
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
                            0 => '80',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '$CC $1',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            11 =>
                [
                    'pattern' => '(\\d{3})(\\d{4})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[3-578]',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '$CC $1',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            12 =>
                [
                    'pattern' => '(\\d{3})(\\d{4})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '1[3-9]',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '$CC $1',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            13 =>
                [
                    'pattern' => '(\\d{2})(\\d{3})(\\d{3})(\\d{4})',
                    'format' => '$1 $2 $3 $4',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[12]',
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
                    'pattern' => '(\\d{2})(\\d{5,6})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '(?:10|2[0-57-9])[19]',
                            1 => '(?:10|2[0-57-9])(?:10|9[56])',
                            2 => '(?:10|2[0-57-9])(?:100|9[56])',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '$CC $1',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            1 =>
                [
                    'pattern' => '(\\d{3})(\\d{5,6})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '3(?:[157]|35|49|9[1-68])|4(?:[17]|2[179]|6[47-9]|8[23])|5(?:[1357]|2[37]|4[36]|6[1-46]|80)|6(?:3[1-5]|6[0238]|9[12])|7(?:01|[1579]|2[248]|3[014-9]|4[3-6]|6[023689])|8(?:1[236-8]|2[5-7]|[37]|8[36-8]|9[1-8])|9(?:0[1-3689]|1[1-79]|[379]|4[13]|5[1-5])|(?:4[35]|59|85)[1-9]',
                            1 => '(?:3(?:[157]\\d|35|49|9[1-68])|4(?:[17]\\d|2[179]|[35][1-9]|6[47-9]|8[23])|5(?:[1357]\\d|2[37]|4[36]|6[1-46]|80|9[1-9])|6(?:3[1-5]|6[0238]|9[12])|7(?:01|[1579]\\d|2[248]|3[014-9]|4[3-6]|6[023689])|8(?:1[236-8]|2[5-7]|[37]\\d|5[1-9]|8[36-8]|9[1-8])|9(?:0[1-3689]|1[1-79]|[379]\\d|4[13]|5[1-5]))[19]',
                            2 => '85[23](?:10|95)|(?:3(?:[157]\\d|35|49|9[1-68])|4(?:[17]\\d|2[179]|[35][1-9]|6[47-9]|8[23])|5(?:[1357]\\d|2[37]|4[36]|6[1-46]|80|9[1-9])|6(?:3[1-5]|6[0238]|9[12])|7(?:01|[1579]\\d|2[248]|3[014-9]|4[3-6]|6[023689])|8(?:1[236-8]|2[5-7]|[37]\\d|5[14-9]|8[36-8]|9[1-8])|9(?:0[1-3689]|1[1-79]|[379]\\d|4[13]|5[1-5]))(?:10|9[56])',
                            3 => '85[23](?:100|95)|(?:3(?:[157]\\d|35|49|9[1-68])|4(?:[17]\\d|2[179]|[35][1-9]|6[47-9]|8[23])|5(?:[1357]\\d|2[37]|4[36]|6[1-46]|80|9[1-9])|6(?:3[1-5]|6[0238]|9[12])|7(?:01|[1579]\\d|2[248]|3[014-9]|4[3-6]|6[023689])|8(?:1[236-8]|2[5-7]|[37]\\d|5[14-9]|8[36-8]|9[1-8])|9(?:0[1-3689]|1[1-79]|[379]\\d|4[13]|5[1-5]))(?:100|9[56])',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '$CC $1',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            2 =>
                [
                    'pattern' => '(\\d{3})(\\d{3})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '(?:4|80)0',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            3 =>
                [
                    'pattern' => '(\\d{2})(\\d{4})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '10|2(?:[02-57-9]|1[1-9])',
                            1 => '10|2(?:[02-57-9]|1[1-9])',
                            2 => '10[0-79]|2(?:[02-57-9]|1[1-79])|(?:10|21)8(?:0[1-9]|[1-9])',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '$CC $1',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            4 =>
                [
                    'pattern' => '(\\d{3})(\\d{3})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '3(?:[3-59]|7[02-68])|4(?:[26-8]|3[3-9]|5[2-9])|5(?:3[03-9]|[468]|7[028]|9[2-46-9])|6|7(?:[0-247]|3[04-9]|5[0-4689]|6[2368])|8(?:[1-358]|9[1-7])|9(?:[013479]|5[1-5])|(?:[34]1|55|79|87)[02-9]',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '$CC $1',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            5 =>
                [
                    'pattern' => '(\\d{3})(\\d{7,8})',
                    'format' => '$1 $2',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '9',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            6 =>
                [
                    'pattern' => '(\\d{4})(\\d{3})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '80',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '$CC $1',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            7 =>
                [
                    'pattern' => '(\\d{3})(\\d{4})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[3-578]',
                        ],
                    'nationalPrefixFormattingRule' => '0$1',
                    'domesticCarrierCodeFormattingRule' => '$CC $1',
                    'nationalPrefixOptionalWhenFormatting' => true,
                ],
            8 =>
                [
                    'pattern' => '(\\d{3})(\\d{4})(\\d{4})',
                    'format' => '$1 $2 $3',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '1[3-9]',
                        ],
                    'nationalPrefixFormattingRule' => '',
                    'domesticCarrierCodeFormattingRule' => '$CC $1',
                    'nationalPrefixOptionalWhenFormatting' => false,
                ],
            9 =>
                [
                    'pattern' => '(\\d{2})(\\d{3})(\\d{3})(\\d{4})',
                    'format' => '$1 $2 $3 $4',
                    'leadingDigitsPatterns' =>
                        [
                            0 => '[12]',
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