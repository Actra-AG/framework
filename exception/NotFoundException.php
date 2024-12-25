<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\exception;

use Exception;
use framework\core\HttpStatusCode;

class NotFoundException extends Exception
{
	public function __construct(string $message = '', HttpStatusCode $code = HttpStatusCode::HTTP_NOT_FOUND)
	{
		if ($message === '') {
			$message = 'Not Found';
		}
		parent::__construct(message: $message, code: $code->value);
	}
}