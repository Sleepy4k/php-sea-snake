<?php

namespace Snake\Core\Http;

class Session {
  /*
  * Check if a given session exists
  *
  * @param string $name
  *
  * @return bool
  */
  public static function exists(string $name = '') {
    return (isset($_SESSION[$name])) ? true : false;
  }

  /*
  * Get a given session
  *
  * @param string $name
  *
  * @return string
  */
  public static function get(string $name = '') {
    return $_SESSION[$name];
  }

  /*
  * Set a given session
  *
  * @param string $name
  * @param string $value
  *
  * @return string
  */
  public static function set(string $name = '', string $value = '') {
    return $_SESSION[$name] = $value;
  }

  /*
  * Delete a given session
  *
  * @param string $name
  *
  * @return void
  */
  public static function delete(string $name = '') {
    if (self::exists($name)) {
      unset($_SESSION[$name]);
    }
  }

  /*
  * Flash a given session
  *
  * @param string $name
  * @param string $string
  *
  * @return string
  */
  public static function flash(string $name = '', string $string = '') {
    if (self::exists($name)) {
      $session = self::get($name);
      self::delete($name);
      return $session;
    } else {
      self::set($name, $string);
    }
  }

  /*
  * Get a given session once then delete it
  *
  * @param string $name
  *
  * @return string
  */
  public static function getOnce(string $name = '') {
    if (self::exists($name)) {
      $session = self::get($name);
      self::delete($name);
      return $session;
    }
  }
}