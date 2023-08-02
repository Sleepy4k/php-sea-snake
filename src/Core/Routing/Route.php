<?php

namespace Snake\Core\Routing;

use Snake\Core\Facade\App;

final class Route {
  public static function get(string $path, $action = null, $middleware = null): Router {
    return static::router()->get($path, $action, $middleware);
  }

  public static function post(string $path, $action = null, $middleware = null): Router {
    return static::router()->post($path, $action, $middleware);
  }

  public static function put(string $path, $action = null, $middleware = null): Router {
    return static::router()->put($path, $action, $middleware);
  }

  public static function patch(string $path, $action = null, $middleware = null): Router {
    return static::router()->patch($path, $action, $middleware);
  }

  public static function delete(string $path, $action = null, $middleware = null): Router {
    return static::router()->delete($path, $action, $middleware);
  }

  public static function options(string $path, $action = null, $middleware = null): Router {
    return static::router()->options($path, $action, $middleware);
  }

  public static function middleware($middlewares): Router {
    return static::router()->middleware($middlewares);
  }

  public static function prefix(string $prefix): Router {
    return static::router()->prefix($prefix);
  }

  public static function controller(string $name): Router {
    return static::router()->controller($name);
  }

  public static function setRouteFromFile(): void {
    require_once baseurl() . 'route/web.php';
    require_once baseurl() . 'route/api.php';
  }

  public static function setRouteFromCacheIfExist(): void {
    $cache = baseurl() . 'cache/routes.php';

    if (!is_file($cache)) {
      static::setRouteFromFile();
    } else {
      $cache = (array) require_once $cache;
      static::router()->setRoutes($cache);
    }
  }

  public static function router(): Router {
    return App::get()->singleton(Router::class);
  }
}