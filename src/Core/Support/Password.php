<?php

namespace Snake\Core\Support;

use Snake\Interface\Support\IPassword;

class Password implements IPassword {
  /**
   * Hash a password
   *
   * @param string $password
   *
   * @return string
   */
  public static function make(string $password): string {
    return password_hash($password, config('password', 'algo'),
      array(
        'cost' => config('password', 'cost')
      )
    );
  }

  /**
   * Check a password
   *
   * @param string $password
   * @param string $hash
   *
   * @return bool
   */
  public static function verify(string $password, string $hash): bool {
    return password_verify($password, $hash);
  }
}