<?php

namespace Snake\Core\Support;

final class Config {
  /**
   * Get a config value
   *
   * @param string $path
   *
   * @return mixed
   */
  public static function get(string $file = 'app', string $variable = 'name')
  {
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