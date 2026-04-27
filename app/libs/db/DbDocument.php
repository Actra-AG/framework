<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

use app\libs\common\Helper;

readonly class DbDocument
{
    public function __construct(
        public int $ID,
        public int $eventID,
        public string $title,
        public string $extension
    ) {
    }

    public function getFilePath(): string
    {
        return Helper::getDocumentDirectory() . $this->ID . '.' . $this->extension;
    }
}