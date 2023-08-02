<?php

namespace Snake\Core\Middleware;

use Closure;
use Snake\Core\Http\Request;

class Middleware {
  private $layers;

  public function __construct(array $layers = [])
  {
    for ($i = (count($layers) - 1); $i >= 0; $i--) {
      $this->layers[] = new $layers[$i];
    }
  }

  private function createLayer(Closure $nextLayer, MiddlewareInterface $layer): Closure
  {
    return function (Request $request) use ($nextLayer, $layer): mixed {
      return $layer->handle($request, $nextLayer);
    };
  }

  public function handle(Request $request, Closure $core): mixed
  {
    $next = array_reduce(
      $this->layers,
      function (Closure $nextLayer, MiddlewareInterface $layer): Closure {
        return $this->createLayer($nextLayer, $layer);
      },
      $core
    );

    return $next($request);
  }
}
