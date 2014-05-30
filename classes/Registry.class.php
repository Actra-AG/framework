<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 20.07.2009	CM	updated
# 26.05.2009	DM	created


/***** static data storage container *****/
class Registry implements ArrayAccess {
  private static $options;

  public function getInstance() {
    return new Registry;
  }

  public static function set($option, &$value) {
    self::$options[$option] = $value;
  }

  public static function get($option) {
    if(isset(self::$options[$option])) {
      return self::$options[$option];
    } else {
      return NULL;
    }
  }

  public static function remove($option) {
    if(isset(self::$options[$option])) {
      unset(self::$options[$option]);
    }
  }

  /***** offsetExists for array access *****/
  public function offsetExists ($offset) {
    return (isset(self::$options[$offset]))?true:false;
  }

  /***** offsetGet for array access *****/
  public function offsetGet ($offset) {
    return self::$options[$offset];
  }

  /***** offsetSet for array access *****/
  public function offsetSet ($offset, $value) {
    self::$options[$offset] = $value;
  }

  /***** offsetUnset for array access *****/
  public function offsetUnset ($offset) {
    if (isset(self::$options[$offset]))
      unset(self::$options[$offset]);
  }
}
?>