<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\common;

use RuntimeException;
use stdClass;

readonly class JsonWebToken
{
	private function __construct(
		public string   $token,
		public stdClass $headers,
		public stdClass $payload,
		private string  $signature,
		private string  $encodedHeader,
		private string  $encodedPayload,
		string          $encodedSignature
	) {
	}

	public function validate(string $signingKey): bool
	{
		return hash_equals(
			known_string: hash_hmac(
				algo: 'sha512',
				data: implode(
					separator: '.',
					array: [
						$this->encodedHeader,
						$this->encodedPayload,
					]
				),
				key: $signingKey,
				binary: true
			),
			user_string: $this->signature
		);
	}

	public function getAgeInSeconds(): int
	{
		return time() - $this->payload->iat;
	}

	public static function encode(stdClass $payload, string $signingKey): JsonWebToken
	{
		$headers = (object)[
			'alg' => 'HS512',
			'typ' => 'JWT',
		];
		$payload->iat = time(); // issued at time
		$encodedHeader = JsonWebToken::base64UrlEncode(string: json_encode(value: $headers));
		$encodedPayload = JsonWebToken::base64UrlEncode(string: json_encode(value: $payload));
		$signature = hash_hmac(
			algo: 'sha512',
			data: implode(
				separator: '.',
				array: [
					$encodedHeader,
					$encodedPayload,
				]
			),
			key: $signingKey,
			binary: true
		);
		$encodedSignature = JsonWebToken::base64UrlEncode(string: $signature);

		return new JsonWebToken(
			token: implode(
				separator: '.',
				array: [
					$encodedHeader,
					$encodedPayload,
					$encodedSignature,
				]
			),
			headers: $headers,
			payload: $payload,
			signature: $signature,
			encodedHeader: $encodedHeader,
			encodedPayload: $encodedPayload,
			encodedSignature: $encodedSignature
		);
	}

	private static function base64UrlEncode(string $string): string
	{
		return str_replace(
			search: ['+', '/', '='],
			replace: ['-', '_', ''],
			subject: base64_encode(string: $string)
		);
	}

	public static function decode(string $token): JsonWebToken
	{
		$jwtArray = explode(
			separator: '.',
			string: $token
		);
		if (count(value: $jwtArray) !== 3) {
			throw new RuntimeException(message: 'Invalid JWT token.');
		}
		$encodedHeader = $jwtArray[0];
		$encodedPayload = $jwtArray[1];
		$encodedSignature = $jwtArray[2];

		return new JsonWebToken(
			token: $token,
			headers: JsonWebToken::jsonDecode(input: JsonWebToken::urlSafeBase64Decode(base64EncodedString: $encodedHeader)),
			payload: JsonWebToken::jsonDecode(input: JsonWebToken::urlSafeBase64Decode(base64EncodedString: $encodedPayload)),
			signature: JsonWebToken::urlSafeBase64Decode(base64EncodedString: $encodedSignature),
			encodedHeader: $encodedHeader,
			encodedPayload: $encodedPayload,
			encodedSignature: $encodedSignature
		);
	}

	private static function urlSafeBase64Decode(string $base64EncodedString): string
	{
		$decodedString = base64_decode(
			string: strtr(
				string: $base64EncodedString,
				from: '-_',
				to: '+/'
			),
			strict: true
		);
		if ($decodedString === false) {
			throw new RuntimeException(message: 'Invalid base63 encoded string: ' . $base64EncodedString);
		}

		return $decodedString;
	}

	private static function jsonDecode(string $input): stdClass
	{
		if (!str_starts_with(haystack: $input, needle: '{')) {
			throw new RuntimeException(message: 'Invalid JSON string: ' . $input);
		}

		return json_decode(
			json: $input,
			associative: false,
			flags: JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR
		);
	}
}