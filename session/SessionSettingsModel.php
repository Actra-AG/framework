<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\session;

readonly class SessionSettingsModel
{
	public function __construct(
		public string $savePath = '',
		public string $individualName = '',
		public ?int   $maxLifeTime = null,
		public bool   $isSameSiteStrict = true
	) {
	}
}