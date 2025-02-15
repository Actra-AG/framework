<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\core;

class LanguageCollection
{
    /** @var Language[] */
    private(set) array $languages = [];

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

    public function hasLanguage(string $languageCode): bool
    {
        return !is_null(value: $this->getLanguageByCode(languageCode: $languageCode));
    }

    public function getLanguageByCode(string $languageCode): ?Language
    {
        return array_find($this->languages, fn($language) => $language->code === $languageCode);
    }

    public function getFirstLanguage(): Language
    {
        return current(array: $this->languages);
    }
}