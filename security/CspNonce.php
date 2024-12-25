<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\security;

use framework\session\AbstractSessionHandler;

class CspNonce
{
	private const string SESSION_INDICATOR = 'security_cspNonce';

	public static function get(): string
	{
		if (!AbstractSessionHandler::enabled()) {
			return '';
		}
		if (!array_key_exists(key: CspNonce::SESSION_INDICATOR, array: $_SESSION)) {
			$_SESSION[CspNonce::SESSION_INDICATOR] = CspNonce::generate();
		}

		return $_SESSION[CspNonce::SESSION_INDICATOR];
	}

	private static function generate(): string
	{
		return base64_encode(string: openssl_random_pseudo_bytes(length: 16));
	}
}