<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\core;

readonly class InputParameter
{
    public function __construct(
        public string $name,
        public bool $isRequired,
        public string $description = ''
    ) {
    }
}