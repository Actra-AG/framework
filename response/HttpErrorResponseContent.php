<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\response;

use framework\common\JsonUtils;
use stdClass;

class HttpErrorResponseContent extends HttpResponseContent
{
	private const string ERROR_STATUS = 'error';

	private function __construct(string $content)
	{
		parent::__construct(content: $content);
	}

	public static function createJsonResponseContent(
		string          $errorMessage,
		null|int|string $errorCode = null,
		?stdClass       $additionalInfo = null
	): HttpResponseContent {
		return new HttpErrorResponseContent(content: JsonUtils::convertToJsonString([
			'status' => HttpErrorResponseContent::ERROR_STATUS,
			'error'  => [
				'message'        => $errorMessage,
				'code'           => $errorCode,
				'additionalInfo' => $additionalInfo,
			],
		]));
	}

	public static function createTextResponseContent(string $errorMessage, null|int|string $errorCode = null): HttpResponseContent
	{
		return new HttpErrorResponseContent(content: 'ERROR: ' . $errorMessage . ' (' . $errorCode . ')');
	}
}