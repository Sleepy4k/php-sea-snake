<?php

namespace Snake\Core\Routing;

use Snake\Core\Facade\App;
use Snake\Interface\Routing\IRoute;

final class Route implements IRoute {
  /**
   * Insert route with any method to routes
   * 
   * @param string $path
   * @param array|string|null $action
   * @param array|string|null $middleware
   * 
   * @return Router
   */
  public static function any(string $path, array|string|null $action = null, array|string|null $middleware = null): Router {
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
  public static function get(string $path, array|string|null $action = null, array|string|null $middleware = null): Router {
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
  public static function post(string $path, array|string|null $action = null, array|string|null $middleware = null): Router {
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
  public static function put(string $path, array|string|null $action = null, array|string|null $middleware = null): Router {
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
  public static function patch(string $path, array|string|null $action = null, array|string|null $middleware = null): Router {
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
  public static function delete(string $path, array|string|null $action = null, array|string|null $middleware = null): Router {
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
  public static function options(string $path, array|string|null $action = null, array|string|null $middleware = null): Router {
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
   * @param array|string $middleware
   *
   * @return Router
   */
  public static function middleware(array|string$middleware): Router {
    return static::router()->middleware($middleware);
  }

  /**
   * Add controller to routes
   *
   * @param string $name
   *
   * @return Router
   */
  public static function controller(string $name): Router {
    return static::router()->controller($name);
  }

  /**
   * Add namespace to routes
   *
   * @param callable $callback
   *
   * @return Router
   */
  public static function namespace(string $namespace): Router {
    return static::router()->namespace($namespace);
  }

  /**
   * Add name to routes
   *
   * @param string $name
   *
   * @return void
   */
  public static function as(string $name): void {
    static::router()->as($name);
  }

  /**
   * Check if a route exists
   *
   * @param string $name
   *
   * @return bool
   */
  public static function hasRoute(string $name): bool {
    return static::router()->hasRoute($name);
  }

  /**
   * Get a route
   *
   * @param string $name
   *
   * @return string|null
   */
  public static function getRoute(string $name): string|null {
    return static::router()->getRoute($name);
  }

  /**
   * Check if the current route is the given route
   *
   * @param string $name
   *
   * @return bool
   */
  public static function isRoute(string $name): bool {
    return static::router()->isRoute($name);
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