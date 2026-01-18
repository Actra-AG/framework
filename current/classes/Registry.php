<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 20.07.2009	CM	updated
# 26.05.2009	DM	created

namespace classes;

use ArrayAccess;

/***** static data storage container *****/
class Registry implements ArrayAccess
{
	private static $options;

	public function getInstance()
	{
		return new Registry;
	}

	public static function set($option, $value)
	{
		self::$options[$option] = $value;
	}

	public static function get($option)
	{
		return self::$options[$option] ?? null;
	}

	public static function remove($option)
	{
		if (isset(self::$options[$option])) {
			unset(self::$options[$option]);
		}
	}

	public function offsetExists(mixed $offset): bool
	{
		return isset(self::$options[$offset]);
	}

	public function offsetGet(mixed $offset): mixed
	{
		return self::$options[$offset];
	}

	public function offsetSet(mixed $offset, mixed $value): void
	{
		self::$options[$offset] = $value;
	}

	public function offsetUnset(mixed $offset): void
	{
		if (isset(self::$options[$offset])) {
			unset(self::$options[$offset]);
		}
	}
}