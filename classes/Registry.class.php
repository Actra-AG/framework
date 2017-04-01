<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 20.07.2009	CM	updated
# 26.05.2009	DM	created


/***** static data storage container *****/
class Registry implements ArrayAccess
{
	private static $options;

	public function getInstance()
	{
		return new Registry;
	}

	public static function set($option, &$value)
	{
		self::$options[$option] = $value;
	}

	public static function get($option)
	{
		if (isset(self::$options[$option])) {
			return self::$options[$option];
		} else {
			return NULL;
		}
	}

	public static function remove($option)
	{
		if (isset(self::$options[$option])) {
			unset(self::$options[$option]);
		}
	}

	/**
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return (isset(self::$options[$offset])) ? true : false;
	}

	/**
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return self::$options[$offset];
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value)
	{
		self::$options[$offset] = $value;
	}

	/**
	 * @param mixed $offset
	 */
	public function offsetUnset($offset)
	{
		if (isset(self::$options[$offset]))
			unset(self::$options[$offset]);
	}
}

/* EOF */