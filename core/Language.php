<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\core;

readonly class Language
{
	public function __construct(
		public string $code,
		public string $locale
	) {
	}
}