<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * .
 * Adapted work based on https://github.com/giggsey/libphonenumber-for-php , which was published
 * with "Apache License Version 2.0, January 2004" ( http://www.apache.org/licenses/ )
 */

return [
	'generalDesc'                   =>
		[
			'NationalNumberPattern'   => '(?:2\\d|3)\\d{8}|(?:[235-8]\\d|41)\\d{6}',
			'PossibleLength'          =>
				[
					0 => 8,
					1 => 9,
					2 => 10,
				],
			'PossibleLengthLocalOnly' =>
				[
					0 => 6,
				],
		],
	'fixedLine'                     =>
		[
			'NationalNumberPattern'   => '(?:2[13]|[35-7][14]|41|8[1468])\\d{6}',
			'ExampleNumber'           => '21212862',
			'PossibleLength'          =>
				[
					0 => 8,
				],
			'PossibleLengthLocalOnly' =>
				[
					0 => 6,
				],
		],
	'mobile'                        =>
		[
			'NationalNumberPattern'   => '20(?:[29]\\d|5[24-689]|7[6-8])\\d{6}',
			'ExampleNumber'           => '2023123456',
			'PossibleLength'          =>
				[
					0 => 10,
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'tollFree'                      =>
		[
			'PossibleLength'          =>
				[
					0 => -1,
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'premiumRate'                   =>
		[
			'PossibleLength'          =>
				[
					0 => -1,
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
			'PossibleLength'          =>
				[
					0 => -1,
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
			'NationalNumberPattern'   => '30\\d{7}',
			'ExampleNumber'           => '301234567',
			'PossibleLength'          =>
				[
					0 => 9,
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
	'id'                            => 'LA',
	'countryCode'                   => 856,
	'internationalPrefix'           => '00',
	'nationalPrefix'                => '0',
	'nationalPrefixForParsing'      => '0',
	'sameMobileAndFixedLinePattern' => false,
	'numberFormat'                  =>
		[
			0 =>
				[
					'pattern'                              => '(\\d{2})(\\d{3})(\\d{3})',
					'format'                               => '$1 $2 $3',
					'leadingDigitsPatterns'                =>
						[
							0 => '2[13]|3[14]|[4-8]',
						],
					'nationalPrefixFormattingRule'         => '0$1',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
			1 =>
				[
					'pattern'                              => '(\\d{2})(\\d{2})(\\d{2})(\\d{3})',
					'format'                               => '$1 $2 $3 $4',
					'leadingDigitsPatterns'                =>
						[
							0 => '3',
						],
					'nationalPrefixFormattingRule'         => '0$1',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
			2 =>
				[
					'pattern'                              => '(\\d{2})(\\d{2})(\\d{3})(\\d{3})',
					'format'                               => '$1 $2 $3 $4',
					'leadingDigitsPatterns'                =>
						[
							0 => '2',
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
	'mobileNumberPortableRegion'    => false,
];