<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\response;

use ArrayObject;
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
        string                    $errorMessage,
        null|int|string           $errorCode = null,
        null|stdClass|ArrayObject $additionalInfo = null
    ): HttpResponseContent
    {
        return new HttpErrorResponseContent(content: JsonUtils::convertToJsonString(
            valueToConvert: [
                'status' => HttpErrorResponseContent::ERROR_STATUS,
                'error' => [
                    'message' => $errorMessage,
                    'code' => $errorCode,
                    'additionalInfo' => $additionalInfo,
                ],
            ]
        ));
    }

    public static function createTextResponseContent(
        string          $errorMessage,
        null|int|string $errorCode = null
    ): HttpResponseContent
    {
        return new HttpErrorResponseContent(content: 'ERROR: ' . $errorMessage . ' (' . $errorCode . ')');
    }
}