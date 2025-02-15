<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\common;

use framework\core\HttpRequest;

class UrlHelper
{
    public static function generateAbsoluteUri(string $relativeOrAbsoluteUri): string
    {
        $components = parse_url(url: $relativeOrAbsoluteUri);
        if (!array_key_exists(key: 'host', array: $components)) {
            if (str_starts_with(haystack: $relativeOrAbsoluteUri, needle: '/')) {
                $directory = '';
            } else {
                $directory = dirname(path: HttpRequest::getURI());
                $directory = ($directory === '/' || $directory === '\\') ? '/' : $directory . '/';
            }
            $absoluteUri = HttpRequest::getProtocol() . '://' . HttpRequest::getHost(
                ) . $directory . $relativeOrAbsoluteUri;
        } else {
            $absoluteUri = $relativeOrAbsoluteUri;
        }

        return $absoluteUri;
    }
}