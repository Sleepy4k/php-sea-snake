<?php

namespace Snake\Core\Http;

class Session {
  /**
   * Check if a given session exists
   *
   * @param string $name
   *
   * @return bool
   */
  public static function exists(string $name): bool {
    return (isset($_SESSION[$name])) ? true : false;
  }

  /**
   * Get a given session
   *
   * @param string $name
   *
   * @return mixed
   */
  public static function get(string $name): mixed {
    return $_SESSION[$name];
  }

  /**
   * Set a given session
   *
   * @param string $name
   * @param mixed $value
   *
   * @return mixed
   */
  public static function set(string $name, mixed $value): mixed {
    return $_SESSION[$name] = $value;
  }

  /**
   * Delete a given session
   *
   * @param string $name
   *
   * @return void
   */
  public static function delete(string $name): void {
    if (self::exists($name)) {
      unset($_SESSION[$name]);
    }
  }

  /**
   * Flash a given session
   *
   * @param string $name
   * @param mixed $value
   *
   * @return string
   */
  public static function flash(string $name, mixed $value): string {
    if (self::exists($name)) {
      $session = self::get($name);
      self::delete($name);
      return $session;
    } else {
      self::set($name, $value);
    }
  }

  /**
   * Get a given session once then delete it
   *
   * @param string $name
   *
   * @return string
   */
  public static function getOnce(string $name): string {
    if (self::exists($name)) {
      $session = self::get($name);
      self::delete($name);
      return $session;
    }
  }
}