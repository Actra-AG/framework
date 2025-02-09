<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\common;

use Exception;

readonly class ValidatedCompanyNumber
{
	public bool $isValid;
	public string $validatedNumber;

	public function __construct(string $inputCompanyNumber, CountryCodeEnum $countryCodeEnum)
	{
		$this->isValid = match ($countryCodeEnum) {
			CountryCodeEnum::CH => $this->validateCH(inputCompanyNumber: $inputCompanyNumber),
			CountryCodeEnum::DK, CountryCodeEnum::NL => $this->validateWithNoSeparator(inputCompanyNumber: $inputCompanyNumber),
			CountryCodeEnum::FI => $this->validateFI(inputCompanyNumber: $inputCompanyNumber),
			CountryCodeEnum::NO => $this->validateNO(inputCompanyNumber: $inputCompanyNumber),
			default => throw new Exception(message: 'To be implemented'),
		};
	}

	private function validateCH(string $inputCompanyNumber): bool
	{
		if (preg_match(
				pattern: '#^CHE-(?<group1>\d{3})[.\s]?(?<group2>\d{3})[.\s]?(?<group3>\d{3})$#',
				subject: $inputCompanyNumber,
				matches: $matches
			) !== 1) {
			return false;
		}
		$this->validatedNumber = 'CHE-' . implode(
				separator: '.',
				array: [
					$matches['group1'],
					$matches['group2'],
					$matches['group3'],
				]
			);

		return true;
	}

	private function validateNO(string $inputCompanyNumber): bool
	{
		if (preg_match(
				pattern: '#^(?<group1>\d{3})[.\s]?(?<group2>\d{3})[.\s]?(?<group3>\d{3})$#',
				subject: $inputCompanyNumber,
				matches: $matches
			) !== 1) {
			return false;
		}
		$this->validatedNumber = implode(
			separator: ' ',
			array: [
				$matches['group1'],
				$matches['group2'],
				$matches['group3'],
			]
		);

		return true;
	}

	private function validateFI(string $inputCompanyNumber): bool
	{
		if (preg_match(
				pattern: '#^(?<group1>\d{7})[.\s-]?(?<group2>\d{1})$#',
				subject: $inputCompanyNumber,
				matches: $matches
			) !== 1) {
			return false;
		}
		$this->validatedNumber = implode(
			separator: '-',
			array: [
				$matches['group1'],
				$matches['group2'],
			]
		);

		return true;
	}

	private function validateWithNoSeparator(string $inputCompanyNumber): bool
	{
		if (preg_match(
				pattern: '#^(?<group1>\d{8})$#',
				subject: $inputCompanyNumber,
				matches: $matches
			) !== 1) {
			return false;
		}
		$this->validatedNumber = $matches['group1'];

		return true;
	}
}