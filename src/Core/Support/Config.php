<?php

namespace Snake\Core\Support;

class Config {
  /**
   * Get a config value
   *
   * @param string $file
   * @param string $variable
   *
   * @return mixed
   */
  public static function get(string $file = 'app', string $variable = 'name') {
    $file = strtolower($file);

    if (file_exists(basepath() . '/config/' . $file . '.config.php')) {
      $config = require basepath() . '/config/' . $file . '.config.php';

      if (isset($config[$variable])) {
        return $config[$variable];
      }
    }

    return null;
  }
}