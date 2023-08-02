<?php

namespace Snake\Core\Facade;

final class App {
  /**
   * App Object instance
   * 
   * @var Application $path
   */
  private $app;

  public static function &new(Application $app): Application {
    static::$app = $app;
    return static::get();
  }

  /**
   * Get app object
   *
   * @return Application
   */
  public static function &get(): Application {
    return static::$app;
  }
}