<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * .
 * Adapted work based on https://github.com/giggsey/libphonenumber-for-php , which was published
 * with "Apache License Version 2.0, January 2004" ( http://www.apache.org/licenses/ )
 */

return [
	'generalDesc'                   =>
		[
			'NationalNumberPattern'   => '(?:1(?:[01467]\\d|[2359][1-9]|8[1-79])|[2-9]\\d)\\d{8}',
			'PossibleLength'          =>
				[
					0 => 10,
					1 => 11,
				],
			'PossibleLengthLocalOnly' =>
				[
					0 => 7,
					1 => 8,
				],
		],
	'fixedLine'                     =>
		[
			'NationalNumberPattern'   => '(?:2(?:0[01]|2[1-9]|3[1-35-8]|4[13-9]|7[1-689]|8[1-578]|9[467])|3(?:1[1-79]|[2458][1-9]|3\\d|7[1-8]|9[1-5])|4(?:1[1-57-9]|[24-7][1-9]|3[1-8]|8[1-35-9]|9[2-689])|5(?:[56]\\d|88|9[1-79])|6(?:1[2-68]|[2-4][1-9]|5[1-3689]|6[1-57-9]|7[1-7]|8[67]|9[4-8])|7(?:[1-467][1-9]|5[13-9]|8[1-69]|9[17])|8(?:1\\d|2[13-689]|3[1-6]|4[124-6]|6[1246-9]|7[1-378]|9[12479])|9(?:1[346-9]|2[1-4]|3[2-46-8]|5[1348]|[69][1-9]|7[12]|8[1-8]))\\d{7}',
			'ExampleNumber'           => '2001234567',
			'PossibleLength'          =>
				[
					0 => 10,
				],
			'PossibleLengthLocalOnly' =>
				[
					0 => 7,
					1 => 8,
				],
		],
	'mobile'                        =>
		[
			'NationalNumberPattern'   => '(?:1(?:2(?:2[1-9]|3[1-35-8]|4[13-9]|7[1-689]|8[1-578]|9[467])|3(?:1[1-79]|[2458][1-9]|3\\d|7[1-8]|9[1-5])|4(?:1[1-57-9]|[24-7][1-9]|3[1-8]|8[1-35-9]|9[2-689])|5(?:[56]\\d|88|9[1-79])|6(?:1[2-68]|[2-4][1-9]|5[1-3689]|6[1-57-9]|7[1-7]|8[67]|9[4-8])|7(?:[1-467][1-9]|5[13-9]|8[1-69]|9[17])|8(?:1\\d|2[13-689]|3[1-6]|4[124-6]|6[1246-9]|7[1-378]|9[12479])|9(?:1[346-9]|2[1-4]|3[2-46-8]|5[1348]|[69][1-9]|7[12]|8[1-8]))|2(?:2[1-9]|3[1-35-8]|4[13-9]|7[1-689]|8[1-578]|9[467])|3(?:1[1-79]|[2458][1-9]|3\\d|7[1-8]|9[1-5])|4(?:1[1-57-9]|[24-7][1-9]|3[1-8]|8[1-35-9]|9[2-689])|5(?:[56]\\d|88|9[1-79])|6(?:1[2-68]|[2-4][1-9]|5[1-3689]|6[1-57-9]|7[1-7]|8[67]|9[4-8])|7(?:[1-467][1-9]|5[13-9]|8[1-69]|9[17])|8(?:1\\d|2[13-689]|3[1-6]|4[124-6]|6[1246-9]|7[1-378]|9[12479])|9(?:1[346-9]|2[1-4]|3[2-46-8]|5[1348]|[69][1-9]|7[12]|8[1-8]))\\d{7}',
			'ExampleNumber'           => '12221234567',
			'PossibleLength'          =>
				[
				],
			'PossibleLengthLocalOnly' =>
				[
					0 => 7,
					1 => 8,
				],
		],
	'tollFree'                      =>
		[
			'NationalNumberPattern'   => '8(?:00|88)\\d{7}',
			'ExampleNumber'           => '8001234567',
			'PossibleLength'          =>
				[
					0 => 10,
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'premiumRate'                   =>
		[
			'NationalNumberPattern'   => '900\\d{7}',
			'ExampleNumber'           => '9001234567',
			'PossibleLength'          =>
				[
					0 => 10,
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'sharedCost'                    =>
		[
			'NationalNumberPattern'   => '300\\d{7}',
			'ExampleNumber'           => '3001234567',
			'PossibleLength'          =>
				[
					0 => 10,
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'personalNumber'                =>
		[
			'NationalNumberPattern'   => '500\\d{7}',
			'ExampleNumber'           => '5001234567',
			'PossibleLength'          =>
				[
					0 => 10,
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'voip'                          =>
		[
			'PossibleLength'          =>
				[
					0 => -1,
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'pager'                         =>
		[
			'PossibleLength'          =>
				[
					0 => -1,
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'uan'                           =>
		[
			'PossibleLength'          =>
				[
					0 => -1,
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'voicemail'                     =>
		[
			'PossibleLength'          =>
				[
					0 => -1,
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'noInternationalDialling'       =>
		[
			'PossibleLength'          =>
				[
					0 => -1,
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'id'                            => 'MX',
	'countryCode'                   => 52,
	'internationalPrefix'           => '0[09]',
	'preferredInternationalPrefix'  => '00',
	'nationalPrefix'                => '01',
	'nationalPrefixForParsing'      => '0(?:[12]|4[45])|1',
	'sameMobileAndFixedLinePattern' => false,
	'numberFormat'                  =>
		[
			0 =>
				[
					'pattern'                              => '(\\d{5})',
					'format'                               => '$1',
					'leadingDigitsPatterns'                =>
						[
							0 => '53',
						],
					'nationalPrefixFormattingRule'         => '',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
			1 =>
				[
					'pattern'                              => '(\\d{2})(\\d{4})(\\d{4})',
					'format'                               => '$1 $2 $3',
					'leadingDigitsPatterns'                =>
						[
							0 => '33|5[56]|81',
						],
					'nationalPrefixFormattingRule'         => '',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => true,
				],
			2 =>
				[
					'pattern'                              => '(\\d{3})(\\d{3})(\\d{4})',
					'format'                               => '$1 $2 $3',
					'leadingDigitsPatterns'                =>
						[
							0 => '[2-9]',
						],
					'nationalPrefixFormattingRule'         => '',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => true,
				],
			3 =>
				[
					'pattern'                              => '(\\d)(\\d{2})(\\d{4})(\\d{4})',
					'format'                               => '$2 $3 $4',
					'leadingDigitsPatterns'                =>
						[
							0 => '1(?:33|5[56]|81)',
						],
					'nationalPrefixFormattingRule'         => '',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => true,
				],
			4 =>
				[
					'pattern'                              => '(\\d)(\\d{3})(\\d{3})(\\d{4})',
					'format'                               => '$2 $3 $4',
					'leadingDigitsPatterns'                =>
						[
							0 => '1',
						],
					'nationalPrefixFormattingRule'         => '',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => true,
				],
		],
	'intlNumberFormat'              =>
		[
			0 =>
				[
					'pattern'                              => '(\\d{2})(\\d{4})(\\d{4})',
					'format'                               => '$1 $2 $3',
					'leadingDigitsPatterns'                =>
						[
							0 => '33|5[56]|81',
						],
					'nationalPrefixFormattingRule'         => '',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => true,
				],
			1 =>
				[
					'pattern'                              => '(\\d{3})(\\d{3})(\\d{4})',
					'format'                               => '$1 $2 $3',
					'leadingDigitsPatterns'                =>
						[
							0 => '[2-9]',
						],
					'nationalPrefixFormattingRule'         => '',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => true,
				],
			2 =>
				[
					'pattern'                              => '(\\d)(\\d{2})(\\d{4})(\\d{4})',
					'format'                               => '$2 $3 $4',
					'leadingDigitsPatterns'                =>
						[
							0 => '1(?:33|5[56]|81)',
						],
					'nationalPrefixFormattingRule'         => '',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => true,
				],
			3 =>
				[
					'pattern'                              => '(\\d)(\\d{3})(\\d{3})(\\d{4})',
					'format'                               => '$2 $3 $4',
					'leadingDigitsPatterns'                =>
						[
							0 => '1',
						],
					'nationalPrefixFormattingRule'         => '',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => true,
				],
		],
	'mainCountryForCode'            => false,
	'leadingZeroPossible'           => false,
	'mobileNumberPortableRegion'    => true,
];