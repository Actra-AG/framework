<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\common;

use Throwable;

class ValidatedEmailAddress
{
	private readonly string $sanitizedValue;
	public readonly string $validatedValue;
	private readonly string $domain;
	public readonly bool $isValidSyntax;
	private ?bool $isResolvable = null;
	public readonly string $lastErrorCode;
	public readonly string $lastErrorMessage;

	public function __construct(string $emailAddress)
	{
		$this->sanitizedValue = mb_strtolower(string: $this->silentlyReplaceInvalidWhitespaces(emailAddress: $emailAddress));
		$this->isValidSyntax = $this->validateSyntax(input: $this->sanitizedValue);
	}

	/**
	 * An Email address has *never* spaces/tabs/newlines in it (they might get into that string by c&p error done by users)
	 */
	private function silentlyReplaceInvalidWhitespaces(string $emailAddress): string
	{
		return trim(string: str_replace(
			search: [
				' ',
				"\t",
				"\n",
				"\r",
				"&#8203;",
				"\xE2\x80\x8C",
				"\xE2\x80\x8B", // https://stackoverflow.com/questions/22600235/remove-unicode-zero-width-space-php
			],
			replace: '',
			subject: $emailAddress
		));
	}

	/**
	 * We purposely do NOT allow commas/semicolons (preventing "multiple" email address entered, where NOT expected)
	 * ':' Will catch "mailto:" copy&paste errors from users, which also result in an invalid email address
	 *
	 * @param string $input
	 *
	 * @return bool
	 */
	private function validateSyntax(string $input): bool
	{
		if ($input === '') {
			$this->lastErrorCode = 'emptyValue';
			$this->lastErrorMessage = 'Empty email address value.';

			return false;
		}
		$emailParts = explode(
			separator: '@',
			string: $input
		);
		if (count(value: $emailParts) !== 2) {
			$this->lastErrorCode = 'atCharacterError';
			$this->lastErrorMessage = 'The email address contains not exactly one at-character (@).';

			return false;
		}
		$local = $emailParts[0];
		$domain = idn_to_ascii(domain: $emailParts[1]);
		if ($domain === false) {
			$this->lastErrorCode = 'invalidDomainName';
			$this->lastErrorMessage = 'The email address contains an invalid domain part.';

			return false;
		}
		$input = $local . '@' . $domain;
		if (filter_var(value: $input, filter: FILTER_VALIDATE_EMAIL) === false) {
			$this->lastErrorCode = 'invalidSyntax';
			$this->lastErrorMessage = 'The FILTER_VALIDATE_EMAIL filter returned false due to an invalid syntax.';

			return false;
		}
		if (!$this->additionalSyntaxValidation(input: $input)) {
			$this->lastErrorCode = 'invalidCharacters';
			$this->lastErrorMessage = 'The additional syntax validation failed.';

			return false;
		}
		$this->validatedValue = $input;
		$this->domain = $domain;

		return true;
	}

	private function additionalSyntaxValidation(string $input): bool
	{
		return (
			preg_match(
				pattern: '/^[a-zA-Z0-9.!#$%&\'*+=?^_`{|}~][a-zA-Z0-9.!#$%&\'*+\-=?^_`{|}~]*@(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9]|[a-zA-Z0-9][-a-zA-Z0-9]*[a-zA-Z0-9])\.)+[a-zA-Z0-9][-a-zA-Z0-9]*[a-zA-Z0-9]$/',
				subject: $input
			) === 1
		);
	}

	public function isResolvable(bool $returnTrueOnDnsGetRecordFailure): bool
	{
		if (!$this->isValidSyntax) {
			return false;
		}
		if (is_null(value: $this->isResolvable)) {
			$this->isResolvable = $this->resolve();
		}
		if ($this->isResolvable) {
			return true;
		}

		return (
			$returnTrueOnDnsGetRecordFailure
			&& $this->lastErrorCode === 'dns_get_record'
		);
	}

	private function resolve(): bool
	{
		$mxRecords = [];
		if (getmxrr(hostname: $this->domain, hosts: $mxRecords)) {
			// Currently, we ignore the note from https://www.php.net/manual/en/function.getmxrr:
			// This function should not be used for the purposes of address verification. Only the mailexchangers found in DNS are returned, however, according
			// to » RFC 2821 when no mail exchangers are listed, hostname itself should be used as the only mail exchanger with a priority of 0.
			// TODO: Check a better solution for the future (e.g. dns_get_record with type "MX")? Requires further checking for possible differences.
			return true;
		}

		// Port 25 fallback check if there's no MX record (or an error occurred)
		try {
			$aRecords = dns_get_record(hostname: $this->domain, type: DNS_A);
		} catch (Throwable $throwable) {
			$this->lastErrorCode = 'dns_get_record';
			$this->lastErrorMessage = $throwable->getMessage();

			return false;
		}
		if (count(value: $aRecords) === 0) {
			$this->lastErrorCode = 'noDnsRecords';
			$this->lastErrorMessage = 'No A-Records found for the domain';

			return false;
		}
		try {
			$connection = fsockopen(
				hostname: $aRecords[0]['ip'],
				port: 25,
				error_code: $errorCode,
				error_message: $errorMessage,
				timeout: 5
			);
		} catch (Throwable $throwable) {
			$this->lastErrorCode = 'fsockopen';
			$this->lastErrorMessage = $throwable->getMessage();

			return false;
		}
		if (!is_resource(value: $connection)) {
			$this->lastErrorCode = 'notResolvable';
			$this->lastErrorMessage = 'Failed to connect to port 25';

			return false;
		}
		fclose(stream: $connection);

		return true;
	}
}