<?php

use Snake\Core\Support\Config;

if (!function_exists('config')) {
  /*
  * Get a config value
  *
  * @param string $path
  *
  * @return mixed
  */
  function config(string $path = '') {
    return Config::get($path);
  }
}