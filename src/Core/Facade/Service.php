<?php

namespace Snake\Core\Facade;

use Closure;
use Bin\Kernel as BinKernel;
use Snake\Core\Http\Request;
use Snake\Core\Routing\Route;
use Snake\Core\Middleware\Middleware;

class Service {
  private $request;
  private $respond;

  public function __construct() {
    $this->request = App::get()->singleton(Request::class);

    $this->bootingProviders();
  }

  private function bootingProviders(): void {
    $services = App::get()->singleton(BinKernel::class)->services();

    foreach ($services as $service) {
      App::get()->make($service)->booting();
    }
  }

  private function registerProvider(): void {
    $services = App::get()->singleton(BinKernel::class)->services();

    foreach ($services as $service) {
      App::get()->clean($service)->registrasi();
    }
  }
  
  private function coreMiddleware(array $route, array $variables): Closure {
    return function () use ($route, $variables): mixed {
      $this->registerProvider();
      return $this->invokeController($route, $variables);
    };
  }

  private function process(array $route, array $variables): int {
    $middleware = new Middleware([
      ...App::get()->singleton(BinKernel::class)->middlewares(),
      ...$route['middleware']
    ]);

    $this->respond->send($middleware->handle($this->request, $this->coreMiddleware($route, $variables)));

    return 0;
  }

  private function invokeController(array $route, array $variables): mixed {
    $controller = $route['controller'];
    $function = $route['function'];

    if ($function === null) {
      return null;
    }

    if ($controller === null) {
      $controller = $function;
      $function = '__invoke';
    }

    array_shift($variables);
    return App::get()->invoke($controller, $function, $variables);
  }

  private function handleOutOfRoute(bool $routeMatch): int {
    if ($routeMatch) {
      $this->respond->send(json_encode([
        'error' => 'Method Not Allowed 405'
      ], 405));

      return 0;
    }

    $this->respond->send(json_encode([
      'error' => 'Not Found 404'
    ], 404));

    return 0;
  }

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

  public function run(): int {
    $url = $this->getValidUrl();
    $path = parse_url($url, PHP_URL_PATH);
    $this->request->__set('REQUEST_URL', $url);

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
          return $this->process($route, $variables);
        }
      }
    }

    return $this->handleOutOfRoute($routeMatch);
  }
}