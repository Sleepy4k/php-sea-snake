<?php

namespace Snake\Core\Middleware;

use Closure;
use Snake\Core\Http\Request;

interface MiddlewareInterface {
  /**
   * Handle the middleware
   *
   * @param Request $request
   * @param Closure $next
   *
   * @return mixed
   */
  public function handle(Request $request, Closure $next);
}