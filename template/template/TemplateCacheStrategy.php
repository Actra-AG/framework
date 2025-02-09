<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\template\template;

use Exception;

abstract class TemplateCacheStrategy
{
	protected bool $saveOnDestruct = false;
	protected string $cachePath;

	public function __construct(string $cachePath)
	{
		if (file_exists($cachePath) === false) {
			throw new Exception('Cache path does not exist: ' . $cachePath);
		}

		$this->cachePath = $cachePath;

		$this->saveOnDestruct = true;
	}

	/**
	 * @param string $tplFile
	 *
	 * @return TemplateCacheEntry|null
	 */
	public abstract function getCachedTplFile(string $tplFile): ?TemplateCacheEntry;

	/**
	 * @param string                  $tplFile
	 * @param TemplateCacheEntry|null $currentCacheEntry
	 * @param string                  $compiledTemplateContent
	 *
	 * @return TemplateCacheEntry Path to the cached template
	 */
	public abstract function addCachedTplFile(string $tplFile, ?TemplateCacheEntry $currentCacheEntry, string $compiledTemplateContent): TemplateCacheEntry;

	public function getCachePath(): string
	{
		return $this->cachePath;
	}

	/**
	 * @param boolean $saveOnDestruct
	 */
	public function setSaveOnDestruct(bool $saveOnDestruct): void
	{
		$this->saveOnDestruct = $saveOnDestruct;
	}
}