<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\db;

final class DbQueryLogList
{
	/** @var DbQueryLogItem[] */
	private static array $stack = [];

	public static function add(DbQueryLogItem $dbQueryLogItem): void
	{
		DbQueryLogList::$stack[] = $dbQueryLogItem;
	}

	public static function getLog(): array
	{
		return DbQueryLogList::$stack;
	}
}