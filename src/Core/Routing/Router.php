<?php

namespace Snake\Core\Routing;

use Closure;
use Snake\Interface\Routing\IRouter;

class Router implements IRouter {
  /**
   * The routes
   *
   * @var array $routes
   */
  protected $routes;

  /**
   * Controller routes
   *
   * @var string|null $controller
   */
  protected $controller;

  /**
   * Prefix for routes
   *
   * @var string|null $prefix
   */
  protected $prefix;

  /**
   * Middleware for routes
   *
   * @var array $middleware
   */
  protected $middleware;

  /**
   * Namespace for routes
   *
   * @var string|null $namespace
   */
  protected $namespace;

  /**
   * Constructor
   *
   * @return void
   */
  public function __construct() {
    $this->routes = [];
    $this->middleware = [];
  }

  /**
   * Insert route to routes
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
      $function = $action[0];
      $controller = $action[1];
    } else if (is_string($action) && str_contains($action, '@')) {
      [$function, $controller] = explode('@', $action);
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
      'name' => null,
      'namespace' => $this->namespace
    ];

    return $this;
  }

  /**
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

  /**
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

  /**
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

  /**
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

  /**
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

  /**
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

  /**
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

  /**
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

  /**
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

  /**
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

  /**
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

  /**
   * Set the name for the next routes
   *
   * @param string $name
   *
   * @return void
   */
  private function name(string $name): void {
    $this->routes[count($this->routes) - 1]['name'] = $name;
  }

  /**
   * Set the name for the next routes
   *
   * @param string $name
   *
   * @return Router
   */
  public function as(string $name): Router {
    $this->name($name);

    return $this;
  }

  /**
   * Get the routes
   *
   * @return array
   */
  public function routes(): array {
    return $this->routes;
  }

  /**
   * Check if a route exists
   *
   * @param string $name
   *
   * @return bool
   */
  public function hasRoute(string $name): bool {
    foreach ($this->routes as $route) {
      if ($route['path'] == $name) {
        return true;
      }
    }

    return false;
  }

  /**
   * Get a route
   *
   * @param string $name
   *
   * @return string|null
   */
  public function getRoute(string $name): string|null {
    foreach ($this->routes as $route) {
      if ($route['path'] == $name) {
        return rtrim(baseurl(), "/") . $route['path'];
      }
    }

    return null;
  }

  /**
   * Check if the current route is the given route
   *
   * @param string $name
   *
   * @return bool
   */
  public function isRoute(string $name): bool {
    $route = $this->getRoute($name);

    if (!is_null($route)) {
      $path = preg_replace('/{(\w+)}/', '([\w-]*)', $route['path']);
      $path = str_replace('/', '\/', $path);

      return preg_match('/^' . $path . '$/', $_SERVER['REQUEST_URI']);
    }

    return false;
  }

  /**
   * Add namespace to routes
   *
   * @param string $namespace
   *
   * @return Router
   */
  public function namespace(string $namespace): Router {
    $this->namespace = $namespace;

    return $this;
  }

  /**
   * Group routes
   *
   * @param Closure $group
   *
   * @return void
   */
  public function group(Closure $group): void {
    $originalController = $this->controller;
    $originalPrefix = $this->prefix;
    $originalMiddleware = $this->middleware;
    $originalRoutes = $this->routes;

    $this->controller = null;
    $this->prefix = null;
    $this->middleware = [];

    $group();

    foreach ($this->routes as &$route) {
      if (!in_array($route, $originalRoutes)) {
        $route['controller'] = $route['controller'] ?? $originalController;
        
        if (!is_null($originalPrefix)) {
          $route['path'] = ($route['path'] != '/') ? preg_replace('/{(\w+)}/', '([\w-]*)', $originalPrefix) . $route['path'] : $originalPrefix;
        }

        if (!empty($originalMiddleware)) {
          $route['middleware'] = array_merge($originalMiddleware, $route['middleware']);
        }
      }
    }

    $this->controller = null;
    $this->prefix = null;
    $this->middleware = [];
  }
}