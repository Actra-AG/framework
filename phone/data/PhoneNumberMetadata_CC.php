<?php
/**
 * @author    Christof Moser <christof.moser@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

// Adapted from https://github.com/google/libphonenumber
return [
	'generalDesc'                   =>
		[
			'NationalNumberPattern'   => '1(?:[0-79]\\d|8[0-24-9])\\d{7}|(?:[148]\\d\\d|550)\\d{6}|1\\d{5,7}',
			'PossibleLength'          =>
				[
					0 => 6,
					1 => 7,
					2 => 8,
					3 => 9,
					4 => 10,
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'fixedLine'                     =>
		[
			'NationalNumberPattern'   => '8(?:51(?:0(?:02|31|60)|118)|91(?:0(?:1[0-2]|29)|1(?:[28]2|50|79)|2(?:10|64)|3(?:[06]8|22)|4[29]8|62\\d|70[23]|959))\\d{3}',
			'ExampleNumber'           => '891621234',
			'PossibleLength'          =>
				[
					0 => 9,
				],
			'PossibleLengthLocalOnly' =>
				[
					0 => 8,
				],
		],
	'mobile'                        =>
		[
			'NationalNumberPattern'   => '483[0-3]\\d{5}|4(?:[0-3]\\d|4[047-9]|5[0-25-9]|6[06-9]|7[02-9]|8[0-2457-9]|9[0-27-9])\\d{6}',
			'ExampleNumber'           => '412345678',
			'PossibleLength'          =>
				[
					0 => 9,
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'tollFree'                      =>
		[
			'NationalNumberPattern'   => '180(?:0\\d{3}|2)\\d{3}',
			'ExampleNumber'           => '1800123456',
			'PossibleLength'          =>
				[
					0 => 7,
					1 => 10,
				],
			'PossibleLengthLocalOnly' =>
				[
				],
		],
	'premiumRate'                   =>
		[
			'NationalNumberPattern'   => '190[0-26]\\d{6}',
			'ExampleNumber'           => '1900123456',
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
			'NationalNumberPattern'   => '13(?:00\\d{3}|45[0-4])\\d{3}|13\\d{4}',
			'ExampleNumber'           => '1300123456',
			'PossibleLength'          =>
				[
					0 => 6,
					1 => 8,
					2 => 10,
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
			'NationalNumberPattern'   => '(?:14(?:5(?:1[0458]|[23][458])|71\\d)|550\\d\\d)\\d{4}',
			'ExampleNumber'           => '550123456',
			'PossibleLength'          =>
				[
					0 => 9,
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
	'id'                            => 'CC',
	'countryCode'                   => 61,
	'internationalPrefix'           => '001[14-689]|14(?:1[14]|34|4[17]|[56]6|7[47]|88)0011',
	'preferredInternationalPrefix'  => '0011',
	'nationalPrefix'                => '0',
	'nationalPrefixForParsing'      => '0|([59]\\d{7})$',
	'nationalPrefixTransformRule'   => '8$1',
	'sameMobileAndFixedLinePattern' => false,
	'numberFormat'                  =>
		[
		],
	'intlNumberFormat'              =>
		[
		],
	'mainCountryForCode'            => false,
	'leadingZeroPossible'           => false,
	'mobileNumberPortableRegion'    => false,
];
