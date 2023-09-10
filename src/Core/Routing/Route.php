<?php

namespace Snake\Core\Routing;

use Snake\Core\Facade\App;

final class Route {
  /**
   * Insert route with any method to routes
   * 
   * @param string $path
   * @param array|string|null $action
   * @param array|string|null $middleware
   * 
   * @return Router
   */
  public static function any(string $path, mixed $action = null, mixed $middleware = null): Router {
    return static::router()->any($path, $action, $middleware);
  }

  /**
   * Insert route with get method to routes
   *
   * @param string $path
   * @param array|string|null $action
   * @param array|string|null $middleware
   *
   * @return Router
   */
  public static function get(string $path, mixed $action = null, mixed $middleware = null): Router {
    return static::router()->get($path, $action, $middleware);
  }

  /**
   * Insert route with post method to routes
   *
   * @param string $path
   * @param array|string|null $action
   * @param array|string|null $middleware
   *
   * @return Router
   */
  public static function post(string $path, mixed $action = null, mixed $middleware = null): Router {
    return static::router()->post($path, $action, $middleware);
  }

  /**
   * Insert route with put method to routes
   *
   * @param string $path
   * @param array|string|null $action
   * @param array|string|null $middleware
   *
   * @return Router
   */
  public static function put(string $path, mixed $action = null, mixed $middleware = null): Router {
    return static::router()->put($path, $action, $middleware);
  }

  /**
   * Insert route with patch method to routes
   *
   * @param string $path
   * @param array|string|null $action
   * @param array|string|null $middleware
   *
   * @return Router
   */
  public static function patch(string $path, mixed $action = null, mixed $middleware = null): Router {
    return static::router()->patch($path, $action, $middleware);
  }

  /**
   * Insert route with delete method to routes
   *
   * @param string $path
   * @param array|string|null $action
   * @param array|string|null $middleware
   *
   * @return Router
   */
  public static function delete(string $path, mixed $action = null, mixed $middleware = null): Router {
    return static::router()->delete($path, $action, $middleware);
  }

  /**
   * Insert route with options method to routes
   *
   * @param string $path
   * @param array|string|null $action
   * @param array|string|null $middleware
   *
   * @return Router
   */
  public static function options(string $path, mixed $action = null, mixed $middleware = null): Router {
    return static::router()->options($path, $action, $middleware);
  }

  /**
   * Add prefix to routes
   *
   * @param string $prefix
   *
   * @return Router
   */
  public static function prefix(string $prefix): Router {
    return static::router()->prefix($prefix);
  }

  /**
   * Add middleware to routes
   *
   * @param mixed $middleware
   *
   * @return Router
   */
  public static function middleware(mixed $middleware): Router {
    return static::router()->middleware($middleware);
  }

  /**
   * Add group to routes
   *
   * @param callable $callback
   *
   * @return Router
   */
  public static function controller(string $name): Router {
    return static::router()->controller($name);
  }

  /**
   * Load all routes
   *
   * @return void
   */
  public static function setRoute(): void {
    require_once basepath() . '/route/web.php';

    static::router()->prefix('/api')->group(function () {
      require_once basepath() . '/route/api.php';
    });
  }

  /**
   * Get router instance
   *
   * @return Router
   */
  public static function router(): Router {
    return App::get()->singleton(Router::class);
  }
}