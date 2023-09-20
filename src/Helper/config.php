<?php

use Snake\Core\Support\DotEnv;
use Snake\Core\Support\Config;

if (!function_exists('config')) {
  /**
   * Get a config value
   *
   * @param string $file
   * @param string $name
   *
   * @return mixed
   */
  function config(string $file = 'app', string $name = 'name') {
    return Config::get($file, $name);
  }
}

if (!function_exists('env')) {
  /**
   * Get an environment variable
   *
   * @param string $key
   * @param mixed $default
   * @param string $path
   * @param string $file
   *
   * @return mixed
   */
  function env(string $key, mixed $default, string $path = __DIR__ . '/../../../../..', string $file = '.env') {
    $env = new DotEnv($path, $file);

    return $env->get($key, $default);
  }
}