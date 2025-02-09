<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\core;

class LanguageCollection
{
	/** @var Language[] */
	private array $languages = [];

	public function __construct(array $languages = [])
	{
		foreach ($languages as $language) {
			$this->add(language: $language);
		}
	}

	public function add(Language $language): void
	{
		$this->languages[] = $language;
	}

	/**
	 * @return Language[]
	 */
	public function getLanguages(): array
	{
		return $this->languages;
	}

	public function getLanguageByCode(string $languageCode): ?Language
	{
		foreach ($this->languages as $language) {
			if ($language->code === $languageCode) {
				return $language;
			}
		}

		return null;
	}

	public function hasLanguage(string $languageCode): bool
	{
		return !is_null(value: $this->getLanguageByCode(languageCode: $languageCode));
	}

	public function getFirstLanguage(): Language
	{
		return current(array: $this->languages);
	}
}