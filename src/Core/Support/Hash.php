<?php

namespace Snake\Core\Support;

class Hash {
  /*
  * Hash a given value
  *
  * @param string $value
  * @param string $salt
  *
  * @return string
  */
  public static function make(string $value = '', string $salt = '') {
    return hash('sha256', $value . $salt);
  }

  /*
  * Generate a salt
  *
  * @param int $length
  *
  * @return string
  */
  public static function salt(int $length = 32) {
    return random_bytes($length);
  }

  /*
  * Generate a unique hash
  *
  * @return string
  */
  public static function unique() {
    return self::make(uniqid(), self::salt());
  }
}