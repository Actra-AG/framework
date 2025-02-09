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
			'NationalNumberPattern'   => '[28]\\d{7,9}|[346]\\d{7}|(?:508|[79]\\d)\\d{6,7}',
			'PossibleLength'          =>
				[
					0 => 8,
					1 => 9,
					2 => 10,
				],
			'PossibleLengthLocalOnly' =>
				[
					0 => 7,
				],
		],
	'fixedLine'                     =>
		[
			'NationalNumberPattern'   => '24099\\d{3}|(?:3[2-79]|[49][2-9]|6[235-9]|7[2-57-9])\\d{6}',
			'ExampleNumber'           => '32345678',
			'PossibleLength'          =>
				[
					0 => 8,
				],
			'PossibleLengthLocalOnly' =>
				[
					0 => 7,
				],
		],
	'mobile'                        =>
		[
			'NationalNumberPattern'   => '2[0-28]\\d{8}|2[0-27-9]\\d{7}|21\\d{6}',
			'ExampleNumber'           => '211234567',
			'PossibleLength'          =>
				[
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'tollFree'                      =>
		[
			'NationalNumberPattern'   => '508\\d{6,7}|80\\d{6,8}',
			'ExampleNumber'           => '800123456',
			'PossibleLength'          =>
				[
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'premiumRate'                   =>
		[
			'NationalNumberPattern'   => '90\\d{6,7}',
			'ExampleNumber'           => '900123456',
			'PossibleLength'          =>
				[
					0 => 8,
					1 => 9,
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'sharedCost'                    =>
		[
			'PossibleLength'          =>
				[
					0 => -1,
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'personalNumber'                =>
		[
			'NationalNumberPattern'   => '70\\d{7}',
			'ExampleNumber'           => '701234567',
			'PossibleLength'          =>
				[
					0 => 9,
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
			'NationalNumberPattern'   => '[28]6\\d{6,7}',
			'ExampleNumber'           => '26123456',
			'PossibleLength'          =>
				[
					0 => 8,
					1 => 9,
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
	'id'                            => 'NZ',
	'countryCode'                   => 64,
	'internationalPrefix'           => '0(?:0|161)',
	'preferredInternationalPrefix'  => '00',
	'nationalPrefix'                => '0',
	'nationalPrefixForParsing'      => '0',
	'sameMobileAndFixedLinePattern' => false,
	'numberFormat'                  =>
		[
			0 =>
				[
					'pattern'                              => '(\\d{3})(\\d{2})(\\d{3})',
					'format'                               => '$1 $2 $3',
					'leadingDigitsPatterns'                =>
						[
							0 => '[89]0',
						],
					'nationalPrefixFormattingRule'         => '0$1',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
			1 =>
				[
					'pattern'                              => '(\\d)(\\d{3})(\\d{4})',
					'format'                               => '$1-$2 $3',
					'leadingDigitsPatterns'                =>
						[
							0 => '24|[346]|7[2-57-9]|9[2-9]',
						],
					'nationalPrefixFormattingRule'         => '0$1',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
			2 =>
				[
					'pattern'                              => '(\\d{3})(\\d{3})(\\d{3,4})',
					'format'                               => '$1 $2 $3',
					'leadingDigitsPatterns'                =>
						[
							0 => '2(?:10|74)|[59]|80',
						],
					'nationalPrefixFormattingRule'         => '0$1',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
			3 =>
				[
					'pattern'                              => '(\\d{2})(\\d{3,4})(\\d{4})',
					'format'                               => '$1 $2 $3',
					'leadingDigitsPatterns'                =>
						[
							0 => '2[028]',
						],
					'nationalPrefixFormattingRule'         => '0$1',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
			4 =>
				[
					'pattern'                              => '(\\d{2})(\\d{3})(\\d{3,5})',
					'format'                               => '$1 $2 $3',
					'leadingDigitsPatterns'                =>
						[
							0 => '2(?:[169]|7[0-35-9])|7|86',
						],
					'nationalPrefixFormattingRule'         => '0$1',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
		],
	'intlNumberFormat'              =>
		[
		],
	'mainCountryForCode'            => false,
	'leadingZeroPossible'           => false,
	'mobileNumberPortableRegion'    => true,
];