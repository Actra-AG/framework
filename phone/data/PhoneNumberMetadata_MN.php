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
			'NationalNumberPattern'   => '[12]\\d{7,9}|[57-9]\\d{7}',
			'PossibleLength'          =>
				[
					0 => 8,
					1 => 9,
					2 => 10,
				],
			'PossibleLengthLocalOnly' =>
				[
					0 => 4,
					1 => 5,
					2 => 6,
				],
		],
	'fixedLine'                     =>
		[
			'NationalNumberPattern'   => '[12](?:3[2-8]|4[2-68]|5[1-4689])\\d{6,7}|(?:11(?:3\\d|4[568])|(?:(?:21|5[0568])\\d|70[0-5])\\d)\\d{4}|[12]2(?:[1-3]\\d{5,6}|7\\d{6})',
			'ExampleNumber'           => '50123456',
			'PossibleLength'          =>
				[
				],
			'PossibleLengthLocalOnly' =>
				[
					0 => 4,
					1 => 5,
					2 => 6,
				],
		],
	'mobile'                        =>
		[
			'NationalNumberPattern'   => '(?:8(?:[05689]\\d|3[01])|9(?:[014-9]\\d|20|3[0-4]))\\d{5}',
			'ExampleNumber'           => '88123456',
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
			'NationalNumberPattern'   => '7(?:100|5(?:0[0579]|1[015]|[389]5|[57][57])|(?:6[0167]|7\\d|8[01])\\d)\\d{4}',
			'ExampleNumber'           => '75153456',
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
	'id'                            => 'MN',
	'countryCode'                   => 976,
	'internationalPrefix'           => '001',
	'nationalPrefix'                => '0',
	'nationalPrefixForParsing'      => '0',
	'sameMobileAndFixedLinePattern' => false,
	'numberFormat'                  =>
		[
			0 =>
				[
					'pattern'                              => '(\\d{2})(\\d{2})(\\d{4})',
					'format'                               => '$1 $2 $3',
					'leadingDigitsPatterns'                =>
						[
							0 => '[12]1',
						],
					'nationalPrefixFormattingRule'         => '0$1',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
			1 =>
				[
					'pattern'                              => '(\\d{4})(\\d{4})',
					'format'                               => '$1 $2',
					'leadingDigitsPatterns'                =>
						[
							0 => '[57-9]',
						],
					'nationalPrefixFormattingRule'         => '',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
			2 =>
				[
					'pattern'                              => '(\\d{3})(\\d{5,6})',
					'format'                               => '$1 $2',
					'leadingDigitsPatterns'                =>
						[
							0 => '[12]2[1-3]',
						],
					'nationalPrefixFormattingRule'         => '0$1',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
			3 =>
				[
					'pattern'                              => '(\\d{4})(\\d{5,6})',
					'format'                               => '$1 $2',
					'leadingDigitsPatterns'                =>
						[
							0 => '[12](?:27|3[2-8]|4[2-68]|5[1-4689])',
							1 => '[12](?:27|3[2-8]|4[2-68]|5[1-4689])[0-3]',
						],
					'nationalPrefixFormattingRule'         => '0$1',
					'domesticCarrierCodeFormattingRule'    => '',
					'nationalPrefixOptionalWhenFormatting' => false,
				],
			4 =>
				[
					'pattern'                              => '(\\d{5})(\\d{4,5})',
					'format'                               => '$1 $2',
					'leadingDigitsPatterns'                =>
						[
							0 => '[12]',
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