<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\exception;

use Exception;
use framework\core\HttpStatusCode;

class UnauthorizedException extends Exception
{
	public function __construct($message = 'Unauthorized', HttpStatusCode $code = HttpStatusCode::HTTP_UNAUTHORIZED)
	{
		parent::__construct(message: $message, code: $code->value);
	}
}