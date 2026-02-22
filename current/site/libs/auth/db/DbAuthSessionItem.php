<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth\db;

readonly class DbAuthSessionItem
{
    public function __construct(
        public int $ID,
        public ?int $parentID,
        public DbAuthUserItem $dbAuthUserItem
    ) {
    }
}