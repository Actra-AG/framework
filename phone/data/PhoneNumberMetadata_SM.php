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
			'NationalNumberPattern'   => '(?:0549|[5-7]\\d)\\d{6}',
			'PossibleLength'          =>
				[
					0 => 8,
					1 => 10,
				],
			'PossibleLengthLocalOnly' =>
				[
					0 => 6,
				],
		],
	'fixedLine'                     =>
		[
			'NationalNumberPattern'   => '0549(?:8[0157-9]|9\\d)\\d{4}',
			'ExampleNumber'           => '0549886377',
			'PossibleLength'          =>
				[
					0 => 10,
				],
			'PossibleLengthLocalOnly' =>
				[
					0 => 6,
				],
		],
	'mobile'                        =>
		[
			'NationalNumberPattern'   => '6[16]\\d{6}',
			'ExampleNumber'           => '66661212',
			'PossibleLength'          =>
				[
					0 => 8,
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
			'NationalNumberPattern'   => '7[178]\\d{6}',
			'ExampleNumber'           => '71123456',
			'PossibleLength'          =>
				[
					0 => 8,
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
			'NationalNumberPattern'   => '5[158]\\d{6}',
			'ExampleNumber'           => '58001110',
			'PossibleLength'          =>
				[
					0 => 8,
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
	'id'                            => 'SM',
	'countryCode'                   => 378,
	'internationalPrefix'           => '00',
	'nationalPrefixForParsing'      => '([89]\\d{5})$',
	'nationalPrefixTransformRule'   => '0549$1',
	'sameMobileAndFixedLinePattern' => false,
	'numberFormat'                  =>
		[
			0 =>
				[
					'pattern'                              => '(\\d{6})',
					'format'                               => '$1',
					'leadingDigitsPatterns'                =>
						[
							0 => '[89]',
						],
					'nationalPrefixFormattingRule'         => '',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
			1 =>
				[
					'pattern'                              => '(\\d{2})(\\d{2})(\\d{2})(\\d{2})',
					'format'                               => '$1 $2 $3 $4',
					'leadingDigitsPatterns'                =>
						[
							0 => '[5-7]',
						],
					'nationalPrefixFormattingRule'         => '',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
			2 =>
				[
					'pattern'                              => '(\\d{4})(\\d{6})',
					'format'                               => '$1 $2',
					'leadingDigitsPatterns'                =>
						[
							0 => '0',
						],
					'nationalPrefixFormattingRule'         => '',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
		],
	'intlNumberFormat'              =>
		[
			0 =>
				[
					'pattern'                              => '(\\d{2})(\\d{2})(\\d{2})(\\d{2})',
					'format'                               => '$1 $2 $3 $4',
					'leadingDigitsPatterns'                =>
						[
							0 => '[5-7]',
						],
					'nationalPrefixFormattingRule'         => '',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
			1 =>
				[
					'pattern'                              => '(\\d{4})(\\d{6})',
					'format'                               => '$1 $2',
					'leadingDigitsPatterns'                =>
						[
							0 => '0',
						],
					'nationalPrefixFormattingRule'         => '',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
		],
	'mainCountryForCode'            => false,
	'leadingZeroPossible'           => false,
	'mobileNumberPortableRegion'    => false,
];