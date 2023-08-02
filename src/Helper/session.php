<?php

use Snake\Core\Facade\App;
use Snake\Core\Http\Session;

if (!function_exists('session')) {
  /*
  * Get the session
  *
  * @return string
  */
  function session(): Session {
    return App::get()->singleton(Session::class);
  }
}