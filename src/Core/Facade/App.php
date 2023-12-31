<?php

namespace Snake\Core\Facade;

final class App {
  /**
   * App Object instance
   * 
   * @var Application $path
   */
  private static $app;

  /**
   * Create new app object
   * 
   * @param Application $app
   * 
   * @return Application
   */
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
