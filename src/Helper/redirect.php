<?php

use Snake\Core\Http\Redirect;

if (!function_exists('redirect')) {
  /**
   * Redirect to a given path
   *
   * @param string $path
   *
   * @return void
   */
  function redirect(string $path = '') {
    Redirect::to($path);
  }
}

if (!function_exists('redirect_with')) {
  /**
   * Redirect to a given path with a message
   *
   * @param string $path
   * @param string $message
   *
   * @return void
   */
  function redirect_with(string $path = '', string $message = '') {
    Redirect::with($path, $message);
  }
}

if (!function_exists('redirect_with_data')) {
  /**
   * Redirect to a given path with a message and data
   *
   * @param string $path
   * @param string $message
   * @param array $data
   *
   * @return void
   */
  function redirect_with_data(string $path = '', string $message = '', array $data = []) {
    Redirect::withData($path, $message, $data);
  }
}

if (!function_exists('redirect_with_errors')) {
  /**
   * Redirect to a given path with a message and errors
   *
   * @param string $path
   * @param string $message
   * @param array $errors
   *
   * @return void
   */
  function redirect_with_errors(string $path = '', string $message = '', array $errors = []) {
    Redirect::withErrors($path, $message, $errors);
  }
}