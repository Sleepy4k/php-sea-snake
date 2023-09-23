<?php

use Snake\Core\View\Sea;

/**
 * Handle exceptions
 * 
 * @param Exception $e
 * 
 * @return void
 */
function ExceptionHandler(Exception $e): void {
  Sea::view('errors.trace', [
    'exception' => [
      'file' => $e->getFile(),
      'line' => $e->getLine(),
      'message' => $e->getMessage(),
      'trace' => $e->getTraceAsString()
    ]
  ]);
}

set_exception_handler("ExceptionHandler");