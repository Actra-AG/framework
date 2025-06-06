<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\core;

use framework\security\CspPolicySettingsModel;
use LogicException;

class EnvironmentSettingsModel
{
    private static ?EnvironmentSettingsModel $registeredInstance = null;

    public function __construct(
        public readonly array $allowedDomains,
        public readonly LanguageCollection $availableLanguages,
        public readonly bool $debug,
        private readonly int $copyrightYear,
        public readonly string $robots,
        public readonly ?CspPolicySettingsModel $cspPolicySettingsModel
    ) {
    }

    public static function register(EnvironmentSettingsModel $environmentSettingsModel): void
    {
        if (!is_null(value: EnvironmentSettingsModel::$registeredInstance)) {
            throw new LogicException(message: 'EnvironmentSettingsModel is already registered.');
        }
        EnvironmentSettingsModel::$registeredInstance = $environmentSettingsModel;
    }

    public static function get(): EnvironmentSettingsModel
    {
        return EnvironmentSettingsModel::$registeredInstance;
    }

    public function renderCopyrightYear(): string
    {
        $copyrightYear = $this->copyrightYear;
        if ($copyrightYear < (int)date(format: 'Y')) {
            return $copyrightYear . '-' . date(format: 'Y');
        }
        return $copyrightYear;
    }
}