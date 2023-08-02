<?php

namespace Snake\Core\Facade;

final class Kernel {
  private static function build(): Application {
    App::new(new Application());
    return App::get();
  }

  public static function web() : Service {
    return static::build()->make(Service::class);
  }
}