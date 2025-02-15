<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\form\model;

class FileDataModel
{
    public function __construct(
        private(set) readonly string $name,
        public string $tmp_name,
        private(set) readonly string $type,
        private(set) readonly int $error,
        private(set) readonly int $size
    ) {
    }
}