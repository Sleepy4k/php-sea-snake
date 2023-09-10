<?php

namespace Snake\Core\Facade;

use Snake\Core\Support\Console;

final class Kernel {
  /**
   * Build the application
   *
   * @return Application
   */
  private static function build(): Application {
    App::new(new Application());
    return App::get();
  }

  /**
   * Build the application for web
   *
   * @return Service
   */
  public static function web() : Service {
    return static::build()->make(Service::class);
  }

  /**
   * Build the application for console
   *
   * @return Console
   */
  public static function console() : Console {
    return static::build()->make(Console::class);
  }
}