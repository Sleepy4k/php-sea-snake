<?php

use Snake\Core\Support\DotEnv;
use Snake\Core\Support\Config;

if (!function_exists('config')) {
  /**
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

if (!function_exists('env')) {
  /**
   * Get an environment variable
   *
   * @param string $key
   * @param mixed $default
   * @param string $path
   *
   * @return mixed
   */
  function env(string $key, mixed $default, string $path = __DIR__ . '/../../../../..') {
    $env = new DotEnv($path);

    return $env->get($key, $default);
  }
}