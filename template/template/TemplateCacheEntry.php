<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\template\template;

readonly class TemplateCacheEntry
{
    public function __construct(
        private(set) string $path,
        private(set) int $changeTime,
        private(set) int $size
    ) {
    }
}