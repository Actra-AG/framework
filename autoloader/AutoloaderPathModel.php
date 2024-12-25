<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\autoloader;

class AutoloaderPathModel
{
	const string MODE_UNDERSCORE = 'underscore';
	const string MODE_NAMESPACE = 'namespace';

	private string $path;
	private string $mode;

	public function __construct(
		public readonly string $name,
		string                 $path,
		string                 $mode,
		public readonly array  $fileSuffixList,
		public readonly string $phpFilePathRemove = ''
	) {
		$this->path = str_replace(search: '/', replace: DIRECTORY_SEPARATOR, subject: $path);
		$this->mode = str_replace(search: '/', replace: DIRECTORY_SEPARATOR, subject: $mode);
	}

	public function getPath(): string
	{
		return $this->path;
	}

	public function getMode(): string
	{
		return $this->mode;
	}
}