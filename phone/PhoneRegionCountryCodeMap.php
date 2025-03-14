<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * .
 * Adapted work based on https://github.com/giggsey/libphonenumber-for-php , which was published
 * with "Apache License Version 2.0, January 2004" ( http://www.apache.org/licenses/ )
 */

namespace framework\phone;

class PhoneRegionCountryCodeMap
{
    private const array COUNTRY_CODE_TO_REGION_CODE_MAP = [
        1 =>
            [
                0 => 'US',
                1 => 'AG',
                2 => 'AI',
                3 => 'AS',
                4 => 'BB',
                5 => 'BM',
                6 => 'BS',
                7 => 'CA',
                8 => 'DM',
                9 => 'DO',
                10 => 'GD',
                11 => 'GU',
                12 => 'JM',
                13 => 'KN',
                14 => 'KY',
                15 => 'LC',
                16 => 'MP',
                17 => 'MS',
                18 => 'PR',
                19 => 'SX',
                20 => 'TC',
                21 => 'TT',
                22 => 'VC',
                23 => 'VG',
                24 => 'VI',
            ],
        7 =>
            [
                0 => 'RU',
                1 => 'KZ',
            ],
        20 =>
            [
                0 => 'EG',
            ],
        27 =>
            [
                0 => 'ZA',
            ],
        30 =>
            [
                0 => 'GR',
            ],
        31 =>
            [
                0 => 'NL',
            ],
        32 =>
            [
                0 => 'BE',
            ],
        33 =>
            [
                0 => 'FR',
            ],
        34 =>
            [
                0 => 'ES',
            ],
        36 =>
            [
                0 => 'HU',
            ],
        PhoneCountryCodes::IT =>
            [
                0 => 'IT',
                1 => 'VA',
            ],
        40 =>
            [
                0 => 'RO',
            ],
        41 =>
            [
                0 => 'CH',
            ],
        43 =>
            [
                0 => 'AT',
            ],
        44 =>
            [
                0 => 'GB',
                1 => 'GG',
                2 => 'IM',
                3 => 'JE',
            ],
        45 =>
            [
                0 => 'DK',
            ],
        46 =>
            [
                0 => 'SE',
            ],
        47 =>
            [
                0 => 'NO',
                1 => 'SJ',
            ],
        48 =>
            [
                0 => 'PL',
            ],
        49 =>
            [
                0 => 'DE',
            ],
        51 =>
            [
                0 => 'PE',
            ],
        52 =>
            [
                0 => 'MX',
            ],
        53 =>
            [
                0 => 'CU',
            ],
        54 =>
            [
                0 => 'AR',
            ],
        55 =>
            [
                0 => 'BR',
            ],
        56 =>
            [
                0 => 'CL',
            ],
        57 =>
            [
                0 => 'CO',
            ],
        58 =>
            [
                0 => 'VE',
            ],
        60 =>
            [
                0 => 'MY',
            ],
        61 =>
            [
                0 => 'AU',
                1 => 'CC',
                2 => 'CX',
            ],
        62 =>
            [
                0 => 'ID',
            ],
        63 =>
            [
                0 => 'PH',
            ],
        64 =>
            [
                0 => 'NZ',
            ],
        65 =>
            [
                0 => 'SG',
            ],
        66 =>
            [
                0 => 'TH',
            ],
        81 =>
            [
                0 => 'JP',
            ],
        82 =>
            [
                0 => 'KR',
            ],
        84 =>
            [
                0 => 'VN',
            ],
        86 =>
            [
                0 => 'CN',
            ],
        90 =>
            [
                0 => 'TR',
            ],
        91 =>
            [
                0 => 'IN',
            ],
        92 =>
            [
                0 => 'PK',
            ],
        93 =>
            [
                0 => 'AF',
            ],
        94 =>
            [
                0 => 'LK',
            ],
        95 =>
            [
                0 => 'MM',
            ],
        98 =>
            [
                0 => 'IR',
            ],
        211 =>
            [
                0 => 'SS',
            ],
        212 =>
            [
                0 => 'MA',
                1 => 'EH',
            ],
        213 =>
            [
                0 => 'DZ',
            ],
        216 =>
            [
                0 => 'TN',
            ],
        218 =>
            [
                0 => 'LY',
            ],
        220 =>
            [
                0 => 'GM',
            ],
        221 =>
            [
                0 => 'SN',
            ],
        222 =>
            [
                0 => 'MR',
            ],
        223 =>
            [
                0 => 'ML',
            ],
        224 =>
            [
                0 => 'GN',
            ],
        225 =>
            [
                0 => 'CI',
            ],
        226 =>
            [
                0 => 'BF',
            ],
        227 =>
            [
                0 => 'NE',
            ],
        228 =>
            [
                0 => 'TG',
            ],
        229 =>
            [
                0 => 'BJ',
            ],
        230 =>
            [
                0 => 'MU',
            ],
        231 =>
            [
                0 => 'LR',
            ],
        232 =>
            [
                0 => 'SL',
            ],
        233 =>
            [
                0 => 'GH',
            ],
        234 =>
            [
                0 => 'NG',
            ],
        235 =>
            [
                0 => 'TD',
            ],
        236 =>
            [
                0 => 'CF',
            ],
        237 =>
            [
                0 => 'CM',
            ],
        238 =>
            [
                0 => 'CV',
            ],
        239 =>
            [
                0 => 'ST',
            ],
        240 =>
            [
                0 => 'GQ',
            ],
        241 =>
            [
                0 => 'GA',
            ],
        242 =>
            [
                0 => 'CG',
            ],
        243 =>
            [
                0 => 'CD',
            ],
        244 =>
            [
                0 => 'AO',
            ],
        245 =>
            [
                0 => 'GW',
            ],
        246 =>
            [
                0 => 'IO',
            ],
        247 =>
            [
                0 => 'AC',
            ],
        248 =>
            [
                0 => 'SC',
            ],
        249 =>
            [
                0 => 'SD',
            ],
        250 =>
            [
                0 => 'RW',
            ],
        251 =>
            [
                0 => 'ET',
            ],
        252 =>
            [
                0 => 'SO',
            ],
        253 =>
            [
                0 => 'DJ',
            ],
        254 =>
            [
                0 => 'KE',
            ],
        255 =>
            [
                0 => 'TZ',
            ],
        256 =>
            [
                0 => 'UG',
            ],
        257 =>
            [
                0 => 'BI',
            ],
        258 =>
            [
                0 => 'MZ',
            ],
        260 =>
            [
                0 => 'ZM',
            ],
        261 =>
            [
                0 => 'MG',
            ],
        262 =>
            [
                0 => 'RE',
                1 => 'YT',
            ],
        263 =>
            [
                0 => 'ZW',
            ],
        264 =>
            [
                0 => 'NA',
            ],
        265 =>
            [
                0 => 'MW',
            ],
        266 =>
            [
                0 => 'LS',
            ],
        267 =>
            [
                0 => 'BW',
            ],
        268 =>
            [
                0 => 'SZ',
            ],
        269 =>
            [
                0 => 'KM',
            ],
        290 =>
            [
                0 => 'SH',
                1 => 'TA',
            ],
        291 =>
            [
                0 => 'ER',
            ],
        297 =>
            [
                0 => 'AW',
            ],
        298 =>
            [
                0 => 'FO',
            ],
        299 =>
            [
                0 => 'GL',
            ],
        350 =>
            [
                0 => 'GI',
            ],
        351 =>
            [
                0 => 'PT',
            ],
        352 =>
            [
                0 => 'LU',
            ],
        353 =>
            [
                0 => 'IE',
            ],
        354 =>
            [
                0 => 'IS',
            ],
        355 =>
            [
                0 => 'AL',
            ],
        356 =>
            [
                0 => 'MT',
            ],
        357 =>
            [
                0 => 'CY',
            ],
        358 =>
            [
                0 => 'FI',
                1 => 'AX',
            ],
        359 =>
            [
                0 => 'BG',
            ],
        370 =>
            [
                0 => 'LT',
            ],
        371 =>
            [
                0 => 'LV',
            ],
        372 =>
            [
                0 => 'EE',
            ],
        373 =>
            [
                0 => 'MD',
            ],
        374 =>
            [
                0 => 'AM',
            ],
        375 =>
            [
                0 => 'BY',
            ],
        376 =>
            [
                0 => 'AD',
            ],
        377 =>
            [
                0 => 'MC',
            ],
        378 =>
            [
                0 => 'SM',
            ],
        380 =>
            [
                0 => 'UA',
            ],
        381 =>
            [
                0 => 'RS',
            ],
        382 =>
            [
                0 => 'ME',
            ],
        383 =>
            [
                0 => 'XK',
            ],
        385 =>
            [
                0 => 'HR',
            ],
        386 =>
            [
                0 => 'SI',
            ],
        387 =>
            [
                0 => 'BA',
            ],
        389 =>
            [
                0 => 'MK',
            ],
        420 =>
            [
                0 => 'CZ',
            ],
        421 =>
            [
                0 => 'SK',
            ],
        423 =>
            [
                0 => 'LI',
            ],
        500 =>
            [
                0 => 'FK',
            ],
        501 =>
            [
                0 => 'BZ',
            ],
        502 =>
            [
                0 => 'GT',
            ],
        503 =>
            [
                0 => 'SV',
            ],
        504 =>
            [
                0 => 'HN',
            ],
        505 =>
            [
                0 => 'NI',
            ],
        506 =>
            [
                0 => 'CR',
            ],
        507 =>
            [
                0 => 'PA',
            ],
        508 =>
            [
                0 => 'PM',
            ],
        509 =>
            [
                0 => 'HT',
            ],
        590 =>
            [
                0 => 'GP',
                1 => 'BL',
                2 => 'MF',
            ],
        591 =>
            [
                0 => 'BO',
            ],
        592 =>
            [
                0 => 'GY',
            ],
        593 =>
            [
                0 => 'EC',
            ],
        594 =>
            [
                0 => 'GF',
            ],
        595 =>
            [
                0 => 'PY',
            ],
        596 =>
            [
                0 => 'MQ',
            ],
        597 =>
            [
                0 => 'SR',
            ],
        598 =>
            [
                0 => 'UY',
            ],
        599 =>
            [
                0 => 'CW',
                1 => 'BQ',
            ],
        670 =>
            [
                0 => 'TL',
            ],
        672 =>
            [
                0 => 'NF',
            ],
        673 =>
            [
                0 => 'BN',
            ],
        674 =>
            [
                0 => 'NR',
            ],
        675 =>
            [
                0 => 'PG',
            ],
        676 =>
            [
                0 => 'TO',
            ],
        677 =>
            [
                0 => 'SB',
            ],
        678 =>
            [
                0 => 'VU',
            ],
        679 =>
            [
                0 => 'FJ',
            ],
        680 =>
            [
                0 => 'PW',
            ],
        681 =>
            [
                0 => 'WF',
            ],
        682 =>
            [
                0 => 'CK',
            ],
        683 =>
            [
                0 => 'NU',
            ],
        685 =>
            [
                0 => 'WS',
            ],
        686 =>
            [
                0 => 'KI',
            ],
        687 =>
            [
                0 => 'NC',
            ],
        688 =>
            [
                0 => 'TV',
            ],
        689 =>
            [
                0 => 'PF',
            ],
        690 =>
            [
                0 => 'TK',
            ],
        691 =>
            [
                0 => 'FM',
            ],
        692 =>
            [
                0 => 'MH',
            ],
        800 =>
            [
                0 => '001',
            ],
        808 =>
            [
                0 => '001',
            ],
        850 =>
            [
                0 => 'KP',
            ],
        852 =>
            [
                0 => 'HK',
            ],
        853 =>
            [
                0 => 'MO',
            ],
        855 =>
            [
                0 => 'KH',
            ],
        856 =>
            [
                0 => 'LA',
            ],
        870 =>
            [
                0 => '001',
            ],
        878 =>
            [
                0 => '001',
            ],
        880 =>
            [
                0 => 'BD',
            ],
        881 =>
            [
                0 => '001',
            ],
        882 =>
            [
                0 => '001',
            ],
        883 =>
            [
                0 => '001',
            ],
        886 =>
            [
                0 => 'TW',
            ],
        888 =>
            [
                0 => '001',
            ],
        960 =>
            [
                0 => 'MV',
            ],
        961 =>
            [
                0 => 'LB',
            ],
        962 =>
            [
                0 => 'JO',
            ],
        963 =>
            [
                0 => 'SY',
            ],
        964 =>
            [
                0 => 'IQ',
            ],
        965 =>
            [
                0 => 'KW',
            ],
        966 =>
            [
                0 => 'SA',
            ],
        967 =>
            [
                0 => 'YE',
            ],
        968 =>
            [
                0 => 'OM',
            ],
        970 =>
            [
                0 => 'PS',
            ],
        971 =>
            [
                0 => 'AE',
            ],
        972 =>
            [
                0 => 'IL',
            ],
        973 =>
            [
                0 => 'BH',
            ],
        974 =>
            [
                0 => 'QA',
            ],
        975 =>
            [
                0 => 'BT',
            ],
        976 =>
            [
                0 => 'MN',
            ],
        977 =>
            [
                0 => 'NP',
            ],
        979 =>
            [
                0 => '001',
            ],
        992 =>
            [
                0 => 'TJ',
            ],
        993 =>
            [
                0 => 'TM',
            ],
        994 =>
            [
                0 => 'AZ',
            ],
        995 =>
            [
                0 => 'GE',
            ],
        996 =>
            [
                0 => 'KG',
            ],
        998 =>
            [
                0 => 'UZ',
            ],
    ];

    public static function getSupportedRegions(): array
    {
        $supportedRegions = [];
        foreach (PhoneRegionCountryCodeMap::COUNTRY_CODE_TO_REGION_CODE_MAP as $regionCodes) {
            foreach ($regionCodes as $regionCode) {
                if ($regionCode !== '001') {
                    $supportedRegions[] = $regionCode;
                }
            }
        }

        return $supportedRegions;
    }

    public static function countryCodeExists(int $countryCodeToCheck): bool
    {
        return (array_key_exists(
            key: $countryCodeToCheck,
            array: PhoneRegionCountryCodeMap::COUNTRY_CODE_TO_REGION_CODE_MAP
        ));
    }

    public static function getRegionCodeForCountryCode(int $countryCallingCode): string
    {
        $regionCodes = array_key_exists(
            key: $countryCallingCode,
            array: PhoneRegionCountryCodeMap::COUNTRY_CODE_TO_REGION_CODE_MAP
        ) ? PhoneRegionCountryCodeMap::COUNTRY_CODE_TO_REGION_CODE_MAP[$countryCallingCode] : null;

        return is_null(value: $regionCodes) ? 'ZZ' : $regionCodes[0];
    }

}