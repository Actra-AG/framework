<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth\db;

readonly class DbAuthGroupItem
{
    public function __construct(
        public int $ID,
        public string $title
    ) {
    }
}