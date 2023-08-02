<?php

namespace Snake\Core\Facade;

use Exception;

abstract class Provider {
  protected $instance;

  public function __construct() {
    $this->instance =  App::get();
  }

  public function registration() {
    throw new Exception('Method registration() not implemented');
  }

  public function boot() {
    throw new Exception('Method boot() not implemented');
  }
}