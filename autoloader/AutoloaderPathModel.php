<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\autoloader;

class AutoloaderPathModel
{
    public const string MODE_UNDERSCORE = 'underscore';
    public const string MODE_NAMESPACE = 'namespace';

    public function __construct(
        public readonly string $name,
        private(set) string $path,
        private(set) string $mode,
        public readonly array $fileSuffixList,
        public readonly string $phpFilePathRemove = ''
    ) {
        $this->path = str_replace(search: '/', replace: DIRECTORY_SEPARATOR, subject: $path);
        $this->mode = str_replace(search: '/', replace: DIRECTORY_SEPARATOR, subject: $mode);
    }
}