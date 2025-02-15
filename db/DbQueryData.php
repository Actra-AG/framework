<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\db;

readonly class DbQueryData
{
    public function __construct(
        public string $query,
        public array $params
    ) {
    }
}