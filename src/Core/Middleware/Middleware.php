<?php

namespace Snake\Core\Middleware;

use Closure;
use Snake\Core\Http\Request;

class Middleware {
  /**
   * The layers
   *
   * @var array $layers
   */
  private $layers;

  /**
   * Constructor
   *
   * @param array $layers
   *
   * @return void
   */
  public function __construct(array $layers = []) {
    for ($i = (count($layers) - 1); $i >= 0; $i--) {
      $this->layers[] = new $layers[$i];
    }
  }

  /**
   * Create layer
   *
   * @param Closure $nextLayer
   * @param MiddlewareInterface $layer
   *
   * @return Closure
   */
  private function createLayer(Closure $nextLayer, MiddlewareInterface $layer): Closure {
    return function (Request $request) use ($nextLayer, $layer): mixed {
      return $layer->handle($request, $nextLayer);
    };
  }

  /**
   * Handle the middleware
   *
   * @param Request $request
   * @param Closure $core
   *
   * @return mixed
   */
  public function handle(Request $request, Closure $core): mixed {
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
