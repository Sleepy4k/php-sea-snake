<?php

namespace Snake\Core\Facade;

use Snake\Core\View\Sea;
use Bin\Kernel as BinKernel;
use Snake\Core\Http\Request;
use Snake\Core\Routing\Route;

class Service {
  /**
   * The request
   *
   * @var Request $request
   */
  private $request;

  /**
   * Constructor
   *
   * @return void
   */
  public function __construct() {
    $this->request = App::get()->singleton(Request::class);
    $this->bootingProviders();
  }

  /**
   * Booting providers
   *
   * @return void
   */
  private function bootingProviders(): void {
    $services = App::get()->singleton(BinKernel::class)->services();

    foreach ($services as $service) {
      App::get()->make($service)->booting();
    }
  }

  /**
   * Invoke controller
   *
   * @param array $route
   * @param array $variables
   *
   * @return int
   */
  private function invokeController(array $route, array $variables): int {
    $controller = $route['controller'];
    $function = $route['function'];

    if ($function === null) {
      return 0;
    }

    if ($controller === null) {
      $controller = $function;
      $function = '__invoke';
    }

    array_shift($variables);
    App::get()->invoke($controller, $function, $variables);

    return 0;
  }

  /**
   * Handle out of route
   *
   * @param bool $routeMatch
   *
   * @return int
   */
  private function handleOutOfRoute(bool $routeMatch): int {
    if ($routeMatch) {
      Sea::view(__DIR__ . '/../../View', 'error', [
        'title' => config('app', 'name'),
        'message' => 'Method Not Allowed 405'
      ]);

      return 0;
    }

    Sea::view(__DIR__ . '/../../View', 'error', [
      'title' => config('app', 'name'),
      'message' => 'Not Found 404'
    ]);

    return 0;
  }

  /**
   * Get valid url
   *
   * @return string
   */
  private function getValidUrl(): string {
    $sep = explode($this->request->server('HTTP_HOST'), baseurl(), 2)[1];

    if (empty($sep)) {
      return $this->request->server('REQUEST_URI');
    }

    $raw = explode($sep, $this->request->server('REQUEST_URI'), 2)[1];

    if (!empty($raw)) {
      return $raw;
    }

    return '/';
  }

  /**
   * Run the application
   *
   * @return int
   */
  public function run(): int {
    $url = $this->getValidUrl();
    $path = parse_url($url, PHP_URL_PATH);
    $this->request->__set('REQUEST_URL', $url);

    if ($path != '/') {
      $path = '/' . trim($path, '/');
    }

    $method = $this->request->method() === 'POST'
      ? strtoupper($this->request->get('_method', 'POST'))
      : $this->request->method();

    $routeMatch = false;

    foreach (Route::router()->routes() as $route) {
      $pattern = '#^' . $route['path'] . '$#';
      $variables = [];

      if (preg_match($pattern, $path, $variables)) {
        $routeMatch = true;

        if ($route['method'] === $method) {
          return $this->invokeController($route, $variables);
        }
      }
    }

    return $this->handleOutOfRoute($routeMatch);
  }
}