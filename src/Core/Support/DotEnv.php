<?php

namespace Snake\Core\Support;

use Snake\Interface\Support\IDotEnv;

final class DotEnv implements IDotEnv {
  /**
   * Path to .env file
   *
   * @var string $path
   */
  protected $path;

  /**
   * Create a new dotenv instance.
   *
   * @param string $path
   * @param string $file
   * 
   * @return void
   */
  public function __construct(string $path, string $file = '.env') {
    if ($path === null) {
      $path = getcwd();
    }

    $this->path = rtrim($path, '/');

    if (!is_readable($this->path . '/' . $file)) {
      if (Config::get('app', 'env') === 'production' && Config::get('app', 'debug')) {
        throw new \InvalidArgumentException(
          sprintf('%s does not exist', $this->path . '/' . $file)
        );
      } else if (Config::get('app', 'debug')) {
        throw new \InvalidArgumentException(
          sprintf('%s does not exist', $this->path . '/' . $file)
        );
      } else {
        return [];
      }
    }

    $this->load($file);
  }

  /**
   * Load environment file and set values to $_ENV and $_SERVER superglobals
   *
   * @param string $file
   * 
   * @return void
   */
  public function load(string $file): void {
    $lines = file($this->path . '/' . $file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
      if (strpos(trim($line), '#') === 0) {
        continue;
      }

      list($name, $value) = explode('=', $line, 2);

      $name = trim($name);
      $value = trim($value);

      if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
        putenv(sprintf('%s=%s', $name, $value));
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
      }
    }
  }

  /**
   * Get an environment variable
   *
   * @param string $key
   * @param mixed $default
   *
   * @return mixed
   */
  public static function get(string $key, mixed $default = null): mixed {
    $value = getenv($key);

    if ($value === false) {
      return $default;
    }

    switch (strtolower($value)) {
      case $value === 'true':
        return true;
      case $value === 'false':
        return false;
      case $value === 'empty':
        return '';
      case $value === 'null':
        return null;
    }

    if (strlen($value) > 1 && substr($value, 0, 1) === '"' && substr($value, -1, 1) === '"') {
      return substr($value, 1, -1);
    }

    return $value;
  }

  /**
   * Set an environment variable
   *
   * @param string $key
   * @param mixed $value
   *
   * @return void
   */
  public static function set(string $key, mixed $value): void {
    putenv(sprintf('%s=%s', $key, $value));
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
  }

  /**
   * Check if an environment variable exists
   *
   * @param string $key
   *
   * @return bool
   */
  public static function has(string $key): bool {
    return getenv($key) !== false;
  }

  /**
   * Clear an environment variable
   *
   * @param string $key
   *
   * @return void
   */
  public static function clear(string $key): void {
    putenv($key);
    unset($_ENV[$key], $_SERVER[$key]);
  }

  /**
   * Get all environment variables
   *
   * @return array
   */
  public static function all(): array {
    return $_ENV;
  }
}