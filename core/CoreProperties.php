<?php
/**
 * @author    Christof Moser <christof.moser@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace framework\core;

class CoreProperties
{
	private string $documentRoot;
	private string $fwRoot;
	private string $siteRoot;
	private string $siteCacheDir;
	private string $siteLogsDir;
	private string $siteSettingsDir;
	private string $siteViewsDir;

	public function __construct(string $documentRoot, string $fwRoot, string $siteRoot)
	{
		$this->documentRoot = str_replace('\\', '/', $documentRoot);
		$this->fwRoot = str_replace('\\', '/', $fwRoot);
		$this->siteRoot = str_replace('\\', '/', $siteRoot);

		$this->siteCacheDir = $siteRoot . 'cache/';
		$this->siteLogsDir = $siteRoot . 'logs/';
		$this->siteSettingsDir = $siteRoot . 'settings/';
		$this->siteViewsDir = $siteRoot . 'view/';
	}

	public function getDocumentRoot(): string
	{
		return $this->documentRoot;
	}

	public function getFwRoot(): string
	{
		return $this->fwRoot;
	}

	public function getSiteRoot(): string
	{
		return $this->siteRoot;
	}

	public function getSiteCacheDir(): string
	{
		return $this->siteCacheDir;
	}

	public function getSiteLogsDir(): string
	{
		return $this->siteLogsDir;
	}

	public function getSiteSettingsDir(): string
	{
		return $this->siteSettingsDir;
	}

	public function getSiteViewsDir(): string
	{
		return $this->siteViewsDir;
	}
}