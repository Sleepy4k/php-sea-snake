<?php

namespace Snake\Core\Http;

class Cookie {
  /**
   * Check if a given cookie exists
   *
   * @param string $name
   *
   * @return bool
   */
  public static function exists(string $name): bool {
    return (isset($_COOKIE[$name])) ? true : false;
  }

  /**
   * Get a given cookie
   *
   * @param string $name
   *
   * @return string
   */
  public static function get(string $name): string {
    return $_COOKIE[$name];
  }

  /**
   * Set a given cookie
   *
   * @param string $name
   * @param string $value
   * @param int $expiry
   *
   * @return bool
   */
  public static function set(string $name, string $value, int $expiry = 0): bool {
    if (setcookie($name, $value, time() + $expiry, '/')) {
      return true;
    }

    return false;
  }

  /**
   * Delete a given cookie
   *
   * @param string $name
   *
   * @return void
   */
  public static function delete(string $name): void {
    self::set($name, '', time() - 1);
  }
}