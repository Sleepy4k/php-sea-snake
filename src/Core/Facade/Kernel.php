<?php

namespace Snake\Core\Facade;

use Snake\Core\Support\Console;

final class Kernel {
  private static function build(): Application {
    App::new(new Application());
    return App::get();
  }

  public static function web() : Service {
    return static::build()->make(Service::class);
  }

  public static function console() : Service {
    return static::build()->make(Console::class);
  }
}