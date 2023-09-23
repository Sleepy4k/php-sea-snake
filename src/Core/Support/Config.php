<?php

namespace Snake\Core\Support;

use Snake\Interface\Support\IConfig;

final class Config implements IConfig {
  /**
   * Get a config value
   *
   * @param string $file
   * @param string $variable
   *
   * @return mixed
   */
  public static function get(string $file = 'app', string $variable = 'name'): mixed {
    if (!file_exists(basepath() . '/config/' . $file . '.config.php')) {
      return null;
    }

    $config = require basepath() . '/config/' . $file . '.config.php';

    if (isset($config[$variable])) {
      return $config[$variable];
    }

    return null;
  }
}