<?php

namespace Snake\Core\Http;

use Exception;
use Snake\Core\Storage\File;

class Request {
  private $requestData;

  private $serverData;

  private $validator;

  public function __construct()
  {
    @$_REQUEST = [...@$_REQUEST ?? [], ...@json_decode(strval(file_get_contents('php://input')), true, 1024) ?? []];
    $this->requestData = [...@$_REQUEST, ...@$_FILES ?? []];
    $this->serverData = @$_SERVER ?? [];
  }

  private function fails(): void
  {
    if ($this->validator->fails()) {
      session()->set('old', $this->all());
      session()->set('error', $this->validator->failed());
      redirect('/');
    }
  }

  public function get($name = null, mixed $defaultValue = null): mixed
  {
    if ($name === null) {
      return $this->requestData;
    }

    return $this->requestData[$name] ?? $defaultValue;
  }

  public function server($name = null, mixed $defaultValue = null): mixed
  {
    if ($name === null) {
      return $this->serverData;
    }

    return $this->serverData[$name] ?? $defaultValue;
  }

  public function method(): string
  {
    return strtoupper($this->server('REQUEST_METHOD'));
  }

  public function throw($error): void
  {
    if ($error instanceof Validator) {
      if ($this->validator instanceof Validator) {
        throw new Exception('Terdapat 2 object validator !');
      }

      $this->validator = $error;
    } else {
      $this->validator->throw($error);
    }

    $this->fails();
  }

  public function validate(array $params = []): array
  {
    $key = array_keys($params);

    $this->validator = Validator::make($this->only($key), $params);
    $this->fails();

    foreach ($key as $k) {
      $this->__set($k, $this->validator->get($k));
    }

    return $this->only($key);
  }

  public function file(string $name): File
  {
    $file = new File($this);
    $file->getFromRequest($name);
    return $file;
  }

  public function all(): array
  {
    return $this->get();
  }

  public function only(array $only): array
  {
    $temp = [];
    foreach ($only as $ol) {
      $temp[$ol] = $this->__get($ol);
    }

    return $temp;
  }

  public function except(array $except): array
  {
    $temp = [];
    foreach ($this->all() as $key => $value) {
      if (!in_array($key, $except)) {
        $temp[$key] = $value;
      }
    }

    return $temp;
  }

  public function __get(string $name): mixed
  {
    return $this->__isset($name) ? $this->requestData[$name] : null;
  }

  public function __set(string $name, mixed $value): void
  {
    $this->requestData[$name] = $value;
  }

  public function __isset(string $name): bool
  {
    return isset($this->requestData[$name]);
  }
}
