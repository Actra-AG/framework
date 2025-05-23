<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\core;

readonly class Language
{
    public function __construct(
        public string $code,
        public string $locale
    ) {
    }
}