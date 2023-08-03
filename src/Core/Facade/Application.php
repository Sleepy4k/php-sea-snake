<?php

namespace Snake\Core\Facade;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionFunction;
use ReflectionException;

class Application {
  private $objectPool;

  public function __construct() {
    if ($this->objectPool === null) {
      $this->objectPool = [];
    }
  }

  public function build(string $name, array $default = []) {
    try {
      $reflector = new ReflectionClass($name);

      $constructor = $reflector->getConstructor();
      $args = is_null($constructor) ? [] : $constructor->getParameters();

      return new $name(...$this->getDependencies($args, $default));
    } catch (ReflectionException $e) {
      throw new Exception($e->getMessage());
    }
  }

  private function getDependencies(array $parameters, array $default = []): array {
    $args = [];
    $id = 0;

    foreach ($parameters as $parameter) {
      $type = $parameter->getType();

      if ($type && !$type->isBuiltin()) {
        $args[] = $this->singleton($type->getName());
        continue;
      }

      $args[] = $default[$id] ?? $parameter->getDefaultValue();
      $id++;
    }

    return $args;
  }

  public function &singleton(string $name, array $default = []): object {
    if (empty($this->objectPool[$name])) {
      $this->objectPool[$name] = $this->build($name, $default);
    }

    if (!is_object($this->objectPool[$name])) {
      $this->objectPool[$name] = $this->build($this->objectPool[$name]);
    }

    return $this->objectPool[$name];
  }
  
  public function &make(string $name, array $default = []): object {
    $this->clean($name);
    return $this->singleton($name, $default);
  }

  public function invoke($name, string $method, array $default = []): mixed {
    if (!is_object($name)) {
      $name = $this->singleton($name);
    }

    try {
      $reflector = new ReflectionClass($name);
      $params = $this->getDependencies($reflector->getMethod($method)->getParameters(), $default);

      return $name->{$method}(...$params);
    } catch (ReflectionException $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function clean(string $name) {
    $object = $this->objectPool[$name] ?? null;
    $this->objectPool[$name] = null;
    unset($this->objectPool[$name]);

    return $object;
  }

  public function resolve(Closure $name, array $default = []): mixed {
    try {
      $reflector = new ReflectionFunction($name);
      $arg = $reflector->getParameters();

      return $name(...$this->getDependencies($arg, $default));
    } catch (ReflectionException $e) {
      throw new Exception($e->getMessage());
    }
  }

  public function bind(string $abstract, $class): void {
    if ($class instanceof Closure) {
      $result = $this->resolve($class, array($this));

      if (!is_object($result)) {
        throw new Exception('The result must be object');
      }

      $class = $result;
    }

    $this->objectPool[$abstract] = $class;
  }
}