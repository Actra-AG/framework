<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\db;

use PDOException;
use RuntimeException;
use Throwable;

class DbRuntimeException extends RuntimeException
{
	/**
	 * Enrich a Throwable with useful information about the actual SQL statement and determine the real Error code from a PDOException.
	 */
	public function __construct(Throwable $throwable, string $sql, array $parameters = [])
	{
		$realCode = $throwable->getCode();
		if ($throwable instanceof PDOException) {
			$realCode = (property_exists(object_or_class: $throwable, property: 'errorInfo') && is_array($throwable->errorInfo) && isset($throwable->errorInfo[1])) ? $throwable->errorInfo[1] : 0;
		}

		$message = $throwable->getMessage() . ';' . PHP_EOL;
		$message .= 'SQL-Parameters: ' . ((count($parameters) !== 0) ? ' "' . implode(separator: '", "', array: $parameters) . '"' : '-none-') . PHP_EOL;
		$message .= 'SQL-String: "' . $sql . '"' . PHP_EOL;

		parent::__construct(message: $message, code: $realCode, previous: $throwable);
	}
}