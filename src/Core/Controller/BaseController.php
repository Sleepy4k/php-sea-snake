<?php

namespace Snake\Core\Controller;

use Snake\Core\View\Sea;
use Snake\Core\Facade\App;
use BadMethodCallException;
use Snake\Core\Http\Request;
use Snake\Core\Http\Validator;

class BaseController {
  /**
   * Validate the request
   *
   * @param Request $request
   * @param array $rules
   *
   * @return Validator
   */
  public static function validate(Request $request, array $rules): Validator {
    return App::get()->singleton(Validator::class, [$request, $rules]);
  }

  /**
   * View the page
   *
   * @param string $view
   * @param array $variables
   *
   * @return void
   */
  public static function view(string $view, array $variables = []): void {
    Sea::view($view, $variables);
  }

  /**
   * Response the data
   *
   * @param array $data
   * @param int $status
   *
   * @return void
   */
  public static function response(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
  }

  /**
   * Execute an action on the controller.
   *
   * @param  string  $method
   * @param  array  $parameters
   *
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function callAction($method, $parameters) {
    return $this->{$method}(...array_values($parameters));
  }

  /**
   * Handle calls to missing methods on the controller.
   *
   * @param  string  $method
   * @param  array  $parameters
   *
   * @return mixed
   *
   * @throws \BadMethodCallException
   */
  public function __call($method, $parameters){
    if (!method_exists($this, $method)) {
      if (config('bin', 'debug') && config('bin', 'env') === 'development') {
        throw new BadMethodCallException(sprintf(
          'Method %s::%s does not exist.', static::class, $method
        ));
      } else {
        static::view('errors.500', [
          'message' => 'Method ' . static::class . '::' . $method . ' does not exist.'
        ]);
      }

      exit;
    }
  }
}