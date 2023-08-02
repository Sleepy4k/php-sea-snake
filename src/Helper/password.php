<?php

use Snake\Core\Support\Password;

if (!function_exists('hash_password')) {
  /*
  * Hash a password
  *
  * @param string $password
  *
  * @return string
  */
  function hash_password(string $password = '') {
    return Password::make($password);
  }
}

if (!function_exists('verify_password')) {
  /*
  * Check a password
  *
  * @param string $password
  * @param string $hash
  *
  * @return bool
  */
  function verify_password(string $password, string $hash){
    return Password::verify($password, $hash);
  }
}