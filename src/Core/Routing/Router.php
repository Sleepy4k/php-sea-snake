<?php

namespace Snake\Core\Routing;

use Closure;

class Router {
  /*
  * The routes
  *
  * @var array $routes
  */
  protected $routes;

  /*
  * Controller routes
  *
  * @var string|null $controller
  */
  protected $controller;

  /*
  * Prefix for routes
  *
  * @var string|null $prefix
  */
  protected $prefix;

  /*
  * Middleware for routes
  *
  * @var array $middleware
  */
  protected $middleware;

  /*
  * Constructor
  *
  * @return void
  */
  public function __construct()
  {
    $this->routes = [];
    $this->middleware = [];
  }

  /*
   * Simpan urlnya.
   *
   * @param string $method
   * @param string $path
   * @param array|string|null $action
   * @param array|string|null $middleware
   * 
   * @return Router
   */
  private function add(string $method, string $path, $action = null, $middleware = null): Router {
    if (is_array($action)) {
      $controller = $action[0];
      $function = $action[1];
    } else {
      $controller = null;
      $function = $action;
    }

    $path = preg_replace('/{(\w+)}/', '([\w-]*)', $path);
    $middleware = is_null($middleware) ? [] : (is_string($middleware) ? array($middleware) : $middleware);

    $this->routes[] = [
      'method' => $method,
      'path' => $path,
      'controller' => $controller,
      'function' => $function,
      'middleware' => $middleware,
      'name' => null
    ];

    return $this;
  }

  /*
  * Add a GET route
  *
  * @param string $path
  * @param array|string|null $action
  * @param array|string|null $middleware
  *
  * @return Router
  */
  public function get(string $path, $action = null, $middleware = null): Router {
    return $this->add('GET', $path, $action, $middleware);
  }

  /*
  * Add a POST route
  *
  * @param string $path
  * @param array|string|null $action
  * @param array|string|null $middleware
  *
  * @return Router
  */
  public function post(string $path, $action = null, $middleware = null): Router {
    return $this->add('POST', $path, $action, $middleware);
  }

  /*
  * Add a PUT route
  *
  * @param string $path
  * @param array|string|null $action
  * @param array|string|null $middleware
  *
  * @return Router
  */
  public function put(string $path, $action = null, $middleware = null): Router {
    return $this->add('PUT', $path, $action, $middleware);
  }

  /*
  * Add a PATCH route
  *
  * @param string $path
  * @param array|string|null $action
  * @param array|string|null $middleware
  *
  * @return Router
  */
  public function patch(string $path, $action = null, $middleware = null): Router {
    return $this->add('PATCH', $path, $action, $middleware);
  }

  /*
  * Add a DELETE route
  *
  * @param string $path
  * @param array|string|null $action
  * @param array|string|null $middleware
  *
  * @return Router
  */
  public function delete(string $path, $action = null, $middleware = null): Router {
    return $this->add('DELETE', $path, $action, $middleware);
  }

  /*
  * Add a OPTIONS route
  *
  * @param string $path
  * @param array|string|null $action
  * @param array|string|null $middleware
  *
  * @return Router
  */
  public function options(string $path, $action = null, $middleware = null): Router {
    return $this->add('OPTIONS', $path, $action, $middleware);
  }

  /*
  * Add a route with multiple methods
  *
  * @param array $methods
  * @param string $path
  * @param array|string|null $action
  * @param array|string|null $middleware
  *
  * @return Router
  */
  public function match(array $methods, string $path, $action = null, $middleware = null): Router {
    foreach ($methods as $method) {
      $this->add($method, $path, $action, $middleware);
    }

    return $this;
  }

  /*
  * Add a route with all methods
  *
  * @param string $path
  * @param array|string|null $action
  * @param array|string|null $middleware
  *
  * @return Router
  */
  public function any(string $path, $action = null, $middleware = null): Router {
    return $this->match(['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'], $path, $action, $middleware);
  }

  /*
  * Set the controller for the next routes
  *
  * @param string $controller
  *
  * @return Router
  */
  public function controller(string $controller): Router {
    $this->controller = $controller;

    return $this;
  }

  /*
  * Set the prefix for the next routes
  *
  * @param string $prefix
  *
  * @return Router
  */
  public function prefix(string $prefix): Router {
    $this->prefix = $prefix;

    return $this;
  }

  /*
  * Set the middleware for the next routes
  *
  * @param array|string $middleware
  *
  * @return Router
  */
  public function middleware($middleware): Router {
    $this->middleware = is_string($middleware) ? array($middleware) : $middleware;

    return $this;
  }

  /*
  * Set the name for the next routes
  *
  * @param string $name
  *
  * @return void
  */
  public function name(string $name): void {
    $this->routes[count($this->routes) - 1]['name'] = $name;
  }

  /*
  * Group routes
  *
  * @param Closure $group
  *
  * @return void
  */
  public function group(Closure $group): void {
    $tempController = $this->controller;
    $tempPrefix = $this->prefix;
    $tempMiddleware = $this->middleware;
    $tempRoutes = $this->routes;

    $this->controller = null;
    $this->prefix = null;
    $this->middleware = [];

    $group();

    foreach ($this->routes as $id => $route) {
      if (!in_array($route, $tempRoutes)) {
        if (!is_null($tempController)) {
          $old = $this->routes[$id]['controller'];
          $this->routes[$id]['controller'] = is_null($old) ? $tempController : $old;
        }

        if (!is_null($tempPrefix)) {
          $old = $this->routes[$id]['path'];
          $prefix = preg_replace('/{(\w+)}/', '([\w-]*)', $tempPrefix);
          $this->routes[$id]['path'] = ($old != '/') ? $prefix . $old : $prefix;
        }

        if (!empty($tempMiddleware)) {
          $result = empty($this->middleware) ? $tempMiddleware : $this->middleware;
          $this->routes[$id]['middleware'] = [...$result, ...$this->routes[$id]['middleware']];
        }
      }
    }

    $this->controller = null;
    $this->prefix = null;
    $this->middleware = [];
  }
}