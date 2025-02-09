<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * .
 * Adapted work based on https://github.com/giggsey/libphonenumber-for-php , which was published
 * with "Apache License Version 2.0, January 2004" ( http://www.apache.org/licenses/ )
 */

namespace framework\phone;

use Exception;

class PhoneParseException extends Exception
{
	public const int EMPTY_STRING = 0;
	public const int INVALID_COUNTRY_CODE = 1;
	public const int NOT_A_NUMBER = 2;
	public const int TOO_SHORT_AFTER_IDD = 3;
	public const int TOO_SHORT_NSN = 4;
	public const int TOO_LONG = 5;
}