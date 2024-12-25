<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\db;

readonly class DbQueryData
{
	public function __construct(
		public string $query,
		public array  $params
	) {
	}
}