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
			'NationalNumberPattern'   => '(?:2|33|5\\d|77|88)\\d{7}|[45]\\d{6}',
			'PossibleLength'          =>
				[
					0 => 7,
					1 => 8,
					2 => 9,
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'fixedLine'                     =>
		[
			'NationalNumberPattern'   => '(?:2\\d{3}|33333)\\d{4}',
			'ExampleNumber'           => '21234567',
			'PossibleLength'          =>
				[
					0 => 8,
					1 => 9,
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'mobile'                        =>
		[
			'NationalNumberPattern'   => '(?:(?:330|555|(?:77|88)\\d)\\d|4[67])\\d{5}|5\\d{6}',
			'ExampleNumber'           => '770123456',
			'PossibleLength'          =>
				[
					0 => 7,
					1 => 9,
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
			'NationalNumberPattern'   => '332(?:02|[34]\\d)\\d{4}',
			'ExampleNumber'           => '332021234',
			'PossibleLength'          =>
				[
					0 => 9,
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
	'id'                            => 'LR',
	'countryCode'                   => 231,
	'internationalPrefix'           => '00',
	'nationalPrefix'                => '0',
	'nationalPrefixForParsing'      => '0',
	'sameMobileAndFixedLinePattern' => false,
	'numberFormat'                  =>
		[
			0 =>
				[
					'pattern'                              => '(\\d)(\\d{3})(\\d{3})',
					'format'                               => '$1 $2 $3',
					'leadingDigitsPatterns'                =>
						[
							0 => '[45]',
						],
					'nationalPrefixFormattingRule'         => '0$1',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
			1 =>
				[
					'pattern'                              => '(\\d{2})(\\d{3})(\\d{3})',
					'format'                               => '$1 $2 $3',
					'leadingDigitsPatterns'                =>
						[
							0 => '2',
						],
					'nationalPrefixFormattingRule'         => '0$1',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
			2 =>
				[
					'pattern'                              => '(\\d{2})(\\d{3})(\\d{4})',
					'format'                               => '$1 $2 $3',
					'leadingDigitsPatterns'                =>
						[
							0 => '[3578]',
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