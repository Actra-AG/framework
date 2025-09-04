<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\session;

readonly class SessionSettingsModel
{
    public function __construct(
        public string $savePath = '',
        public string $individualName = '',
        public int $maxLifeTime = 3600,
        public int $gcProbability = 1,
        public int $gcDivisor = 100000,
        public bool $isSameSiteStrict = true
    ) {
    }
}