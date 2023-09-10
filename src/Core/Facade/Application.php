<?php

namespace Snake\Core\Facade;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionFunction;
use ReflectionException;

class Application {
  /**
   * The object pool
   *
   * @var array $objectPool
   */
  private $objectPool;

  /**
   * Constructor
   *
   * @return void
   */
  public function __construct() {
    if ($this->objectPool === null) {
      $this->objectPool = [];
    }
  }

  /**
   * Build the object
   *
   * @param string $name
   * @param array $default
   *
   * @return object
   * 
   * @throws Exception
   */
  public function build(string $name, array $default = []): object {
    try {
      $reflector = new ReflectionClass($name);

      $constructor = $reflector->getConstructor();
      $args = is_null($constructor) ? [] : $constructor->getParameters();

      return new $name(...$this->getDependencies($args, $default));
    } catch (ReflectionException $e) {
      throw new Exception($e->getMessage());
    }
  }

  /**
   * Get the dependencies
   *
   * @param array $parameters
   * @param array $default
   *
   * @return array
   */
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

  /**
   * Get the object
   *
   * @param string $name
   * @param array $default
   *
   * @return object
   */
  public function &singleton(string $name, array $default = []): object {
    if (empty($this->objectPool[$name])) {
      $this->objectPool[$name] = $this->build($name, $default);
    }

    if (!is_object($this->objectPool[$name])) {
      $this->objectPool[$name] = $this->build($this->objectPool[$name]);
    }

    return $this->objectPool[$name];
  }

  /**
   * Make the object
   *
   * @param string $name
   * @param array $default
   *
   * @return object
   */
  public function &make(string $name, array $default = []): object {
    $this->clean($name);
    return $this->singleton($name, $default);
  }

  /**
   * Invoke the object
   *
   * @param string|object $name
   * @param string $method
   * @param array $default
   *
   * @return mixed
   * 
   * @throws Exception
   */
  public function invoke($name, string $method, array $default = []) {
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

  /**
   * Clean the object
   *
   * @param string $name
   *
   * @return object|null
   */
  public function clean(string $name) {
    $object = $this->objectPool[$name] ?? null;
    $this->objectPool[$name] = null;
    unset($this->objectPool[$name]);

    return $object;
  }

  /**
   * Resolve the object
   *
   * @param Closure $name
   * @param array $default
   *
   * @return mixed
   * 
   * @throws Exception
   */
  public function resolve(Closure $name, array $default = []) {
    try {
      $reflector = new ReflectionFunction($name);
      $arg = $reflector->getParameters();

      return $name(...$this->getDependencies($arg, $default));
    } catch (ReflectionException $e) {
      throw new Exception($e->getMessage());
    }
  }

  /**
   * Bind the object
   *
   * @param string $abstract
   * @param mixed $class
   *
   * @return void
   * 
   * @throws Exception
   */
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