<?php

namespace Snake\Core\Middleware;

use Closure;
use Snake\Core\Http\Request;

interface MiddlewareInterface {
  public function handle(Request $request, Closure $next): mixed;
}