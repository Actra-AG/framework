<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\template\template;

use Exception;

abstract class TemplateCacheStrategy
{
    public function __construct(
        protected(set) string $cachePath,
        public bool $saveOnDestruct = true
    ) {
        if (file_exists(filename: $cachePath) === false) {
            throw new Exception(message: 'Cache path does not exist: ' . $cachePath);
        }
    }

    /**
     * @param string $tplFile
     *
     * @return TemplateCacheEntry|null
     */
    abstract public function getCachedTplFile(string $tplFile): ?TemplateCacheEntry;

    /**
     * @param string $tplFile
     * @param TemplateCacheEntry|null $currentCacheEntry
     * @param string $compiledTemplateContent
     *
     * @return TemplateCacheEntry Path to the cached template
     */
    abstract public function addCachedTplFile(
        string $tplFile,
        ?TemplateCacheEntry $currentCacheEntry,
        string $compiledTemplateContent
    ): TemplateCacheEntry;
}