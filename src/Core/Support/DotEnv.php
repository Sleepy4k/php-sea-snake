<?php

namespace Snake\Core\Support;

class DotEnv {
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
   */
  public function __construct(string $path = '') {
    if ($path === null) {
      $path = getcwd();
    }

    $this->path = rtrim($path, '/');

    if (!is_readable($this->path . '/.env')) {
      throw new \InvalidArgumentException(
        sprintf('%s does not exist', $this->path . '/.env')
      );
    }

    $this->load();
  }

  /**
   * Load environment file and set values to $_ENV and $_SERVER superglobals
   *
   * @return void
   */
  protected function load(): void {
    $lines = file($this->path . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

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