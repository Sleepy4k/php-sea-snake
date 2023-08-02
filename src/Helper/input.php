<?php

use Snake\Core\Http\Input;

if (!function_exists('input')) {
  /*
  * Get an input value
  *
  * @param string $key
  *
  * @return mixed
  */
  function input(string $key = '') {
    return Input::get($key);
  }
}

if (!function_exists('input_exists')) {
  /*
  * Check if an input exists
  *
  * @param string $type
  *
  * @return bool
  */
  function input_exists(string $type = '') {
    return Input::exists($type);
  }
}

if (!function_exists('input_all')) {
  /*
  * Get all inputs
  *
  * @return array
  */
  function input_all() {
    return Input::all();
  }
}

if (!function_exists('input_old')) {
  /*
  * Get an old input value
  *
  * @param string $key
  *
  * @return mixed
  */
  function input_old(string $key = '') {
    return Input::old($key);
  }
}

if (!function_exists('input_set_old')) {
  /*
  * Set an old input value
  *
  * @param string $key
  * @param string $value
  *
  * @return void
  */
  function input_set_old(string $key = '', string $value = '') {
    return Input::set($key, $value);
  }
}

if (!function_exists('input_delete_old')) {
  /*
  * Delete an old input value
  *
  * @param string $key
  *
  * @return void
  */
  function input_delete_old(string $key = '') {
    return Input::delete($key);
  }
}