<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

readonly class DbClub
{
    public function __construct(
        public int $ID,
        public string $name
    ) {
    }
}