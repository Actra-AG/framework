<?php
/**
 * @author    Christof Moser <christof.moser@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace framework\exception;

use Exception;

class UnauthorizedException extends Exception
{
	public function __construct($message = 'Unauthorized', $code = 9999)
	{
		parent::__construct($message, $code);
	}
}