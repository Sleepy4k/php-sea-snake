<?php

namespace Snake\Core\Support;

class Password {
  /*
  * Hash a password
  *
  * @param string $password
  *
  * @return string
  */
  public static function make(string $password = '') {
    return password_hash($password, Config::get('password/algo'),
      array(
        'cost' => Config::get('password/cost')
      )
    );
  }

  /*
  * Check a password
  *
  * @param string $password
  * @param string $hash
  *
  * @return bool
  */
  public static function verify(string $password, string $hash){
    return password_verify($password, $hash);
  }
}