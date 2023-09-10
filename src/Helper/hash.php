<?php

use Snake\Core\Support\Hash;

if (!function_exists('hash_value')) {
  /**
   * Hash a given value
   *
   * @param string $value
   * @param string $salt
   *
   * @return string
   */
  function hash_value(string $value = '', string $salt = '') {
    return Hash::make($value, $salt);
  }
}

if (!function_exists('hash_salt')) {
  /**
   * Generate a salt
   *
   * @param int $length
   *
   * @return string
   */
  function hash_salt(int $length = 32) {
    return Hash::salt($length);
  }
}

if (!function_exists('hash_unique')) {
  /**
   * Generate a unique hash
   *
   * @return string
   */
  function hash_unique() {
    return Hash::unique();
  }
}