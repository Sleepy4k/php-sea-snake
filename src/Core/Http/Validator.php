<?php

namespace Snake\Core\Http;

use Snake\Core\Support\Hash;

class Validator {
  /**
   * The data to be validated
   *
   * @var array
   */
  private $data;

  /**
   * The errors
   *
   * @var array
   */
  private $errors;

  /**
   * Constructor
   *
   * @param array $data
   * @param array $rule
   *
   * @return void
   */
  public function __construct(array $data, array $rule) {
    $this->setData($data);
    $this->validate($rule);
  }

  /**
   * Set the data
   *
   * @param array $data
   *
   * @return void
   */
  private function setData(array $data): void {
    $this->data = $data;
    $this->errors = [];
  }

  /**
   * Validate the rules
   *
   * @param string $param
   * @param array $rules
   *
   * @return void
   */
  private function validateRule(string $param, array $rules): void {
    foreach ($rules as $rule) {
      if (!empty($this->errors[$param])) {
        continue;
      }

      $value = $this->__get($param);

      if (is_array($value)) {
        $this->validateFile($param, $value, $rule);
        continue;
      }

      $this->validateRequest($param, $value, $rule);
    }
  }

  /**
   * Validate the request
   *
   * @param string $param
   * @param mixed $value
   * @param string $rule
   *
   * @return void
   */
  private function validateRequest(string $param, mixed $value, string $rule): void {
    if (str_contains($rule, 'min')) {
      $min = intval(explode(':', $rule)[1]);

      if (strlen($value) < $min) {
        $this->setError($param, 'minimum length', $min);
      }
    } elseif (str_contains($rule, 'max')) {
      $max = intval(explode(':', $rule)[1]);

      if (strlen($value) > $max) {
        $this->setError($param, 'maximum length', $max);
      }
    } elseif (str_contains($rule, 'same')) {
      $target = explode(':', $rule)[1];

      if ($this->__get($target) != $value) {
        $this->setError($param, 'not same as', $target);
      }
    } elseif (str_contains($rule, 'unique')) {
      $command = explode(':', $rule);

      $model = 'Bin\Models\\' . (empty($command[1]) ? 'User' : ucfirst($command[1]));
      $column = $command[2] ?? $param;

      $data = (new $model)->find($value, $column);

      if ($data->{$column}) {
        $this->setError($param, 'sudah ada !');
      }

      $data = null;
      unset($data);
    } else {
      switch ($rule) {
        case 'required':
          if (!$this->__isset($param) || empty(trim(strval($value)))) {
            $this->setError($param, 'dibutuhkan !');
          }
          break;
        case 'email':
          if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->__set($param, filter_var($value, FILTER_SANITIZE_EMAIL));
          } else {
            $this->setError($param, 'ilegal atau tidak sah !');
          }

          break;
        case 'dns':
          if (!checkdnsrr(explode('@', $value)[1])) {
            $this->setError($param, 'ilegal atau tidak sah !');
          }

          break;
        case 'url':
          if (filter_var($value, FILTER_VALIDATE_URL)) {
            $this->__set($param, filter_var($value, FILTER_SANITIZE_URL));
          } else {
            $this->setError($param, 'ilegal atau tidak sah !');
          }

          break;
        case 'int':
          if (is_numeric($value)) {
            $this->__set($param, intval($value));
          } else {
            $this->setError($param, 'harus angka !');
          }

          break;
        case 'float':
          if (is_numeric($value)) {
            $this->__set($param, floatval($value));
          } else {
            $this->setError($param, 'harus desimal !');
          }

          break;
        case 'str':
          $this->__set($param, strval($value));
          break;
  
        case 'bool':
          $this->__set($param, boolval($value));
          break;
  
        case 'slug':
          $this->__set($param, preg_replace('/[^\w-]/', '', $value));

          break;
        case 'safe':
          $bad = [...array_map('chr', range(0, 31)), ...['\\', '/', ':', '*', '?', '"', '<', '>', '|']];
          $this->__set($param, str_replace($bad, '', $value));

          break;
        case 'hash':
          $this->__set($param, Hash::make($value));

          break;
        case 'trim':
          $this->__set($param, trim($value));

          break;
      }
    }
  }

  /**
   * Check for malicious keywords
   *
   * @param string $file
   *
   * @return bool
   */
  private function maliciousKeywords(string $file): bool {
    $malicious = implode('|', [
      '\\/bin\\/bash',
      '__HALT_COMPILER',
      'Monolog',
      'PendingRequest',
      '\\<script',
      'ThinkPHP',
      'phar',
      'phpinfo',
      '\\<\\?php',
      '\\$_GET',
      '\\$_POST',
      '\\$_SESSION',
      '\\$_REQUEST',
      'whoami',
      'python',
      'composer',
      'passthru',
      'shell_exec',
      'PHPShell',
      'exec',
    ]);

    return (bool) preg_match(sprintf('/(%s)/im', $malicious), strval(file_get_contents($file, true)));
  }

  /**
   * Validate the file
   *
   * @param string $param
   * @param array $value
   * @param string $rule
   *
   * @return void
   */
  private function validateFile(string $param, array $value, string $rule): void {
    $error = [
      0 => false,
      1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
      2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
      3 => 'The uploaded file was only partially uploaded',
      4 => false,
      6 => 'Missing a temporary folder',
      7 => 'Failed to write file to disk.',
      8 => 'A PHP extension stopped the file upload.',
    ];

    $err = $error[$value['error']];

    if ($err) {
      @unlink($value['tmp_name']);
      $this->setError($param, $err);
    } else {
      switch (true) {
        case $rule == 'required':
          if ($value['error'] === 4 || $value['size'] === 0 || empty($value['name'])) {
            @unlink($value['tmp_name']);
            $this->setError($param, 'dibutuhkan !');
          }

          break;
        case str_contains($rule, 'min'):
          $min = intval(explode(':', $rule)[1]) * 1024;
          if ($value['size'] < $min) {
            @unlink($value['tmp_name']);
            $this->setError($param, 'ukuran minimal', formatFileBytes($min));
          }

          break;
        case str_contains($rule, 'max'):
          $max = intval(explode(':', $rule)[1]) * 1024;

          if ($value['size'] > $max) {
            @unlink($value['tmp_name']);
            $this->setError($param, 'ukuran maximal', formatFileBytes($max));
          }

          break;
        case str_contains($rule, 'mimetypes'):
          $mime = explode(':', $rule)[1];

          if (!in_array($value['type'], explode(',', $mime))) {
            @unlink($value['tmp_name']);
            $this->setError($param, 'diperbolehkan', $mime);
          }

          break;
        case str_contains($rule, 'mimes'):
          $mime = explode(':', $rule)[1];

          if (!in_array(pathinfo($value['full_path'], PATHINFO_EXTENSION), explode(',', $mime))) {
            @unlink($value['tmp_name']);
            $this->setError($param, 'diperbolehkan', $mime);
          }

          break;
        case $rule == 'safe':
          if ($this->maliciousKeywords($value['tmp_name'])) {
            @unlink($value['tmp_name']);
            $this->setError($param, 'file berbahaya !');
          }

          break;
      }
    }
  }

  /**
   * Set the error
   *
   * @param string $param
   * @param string $alert
   * @param mixed $optional
   *
   * @return void
   */
  private function setError(string $param, string $alert, mixed $optional = null): void {
    if (empty($this->errors[$param])) {
      $this->errors[$param] = $param . ' ' . $alert . ($optional ? ' ' . strval($optional) : '');
    }
  }

  /**
   * Make a new validator
   *
   * @param array $data
   * @param array $rule
   *
   * @return Validator
   */
  public static function make(array $data, array $rule): Validator {
    return new static($data, $rule);
  }

  /**
   * Validate the rules
   *
   * @param array $rules
   *
   * @return void
   */
  public function validate(array $rules): void {
    foreach ($rules as $param => $rule) {
      $this->validateRule($param, $rule);
    }
  }

  /**
   * Check if the validation fails
   *
   * @return bool
   */
  public function fails(): bool {
    return !empty($this->errors);
  }

  /**
   * Get the errors
   *
   * @return array
   */
  public function failed(): array {
    return $this->fails() ? $this->errors : [];
  }

  /**
   * Get the error messages
   *
   * @return array
   */
  public function messages(): array {
    return array_values($this->failed());
  }

  /**
   * Throw an error
   *
   * @param array $error
   *
   * @return void
   */
  public function throw(array $error = []): void {
    $this->errors = [...$this->failed(), ...$error];
  }

  /**
   * Get the data
   *
   * @param array $only
   *
   * @return array
   */
  public function only(array $only): array {
    $temp = [];

    foreach ($only as $ol) {
      $temp[$ol] = $this->__get($ol);
    }

    return $temp;
  }

  /**
   * Get the data
   *
   * @param array $except
   *
   * @return array
   */
  public function except(array $except): array {
    $temp = [];

    foreach ($this->get() as $key => $value) {
      if (!in_array($key, $except)) {
        $temp[$key] = $value;
      }
    }

    return $temp;
  }

  /**
   * Get the data
   *
   * @param string $name
   * @param mixed $defaultValue
   *
   * @return mixed
   */
  public function get(string $name = null, mixed $defaultValue = null): mixed {
    if ($name === null) {
      return $this->data;
    }

    return $this->data[$name] ?? $defaultValue;
  }

  /**
   * Get the data
   *
   * @param string $name
   *
   * @return mixed
   */
  public function __get(string $name): mixed {
    return $this->__isset($name) ? $this->data[$name] : null;
  }

  /**
   * Set the data
   *
   * @param string $name
   * @param mixed $value
   *
   * @return void
   */
  public function __set(string $name, mixed $value): void {
    $this->data[$name] = $value;
  }

  /**
   * Check if the data exists
   *
   * @param string $name
   *
   * @return bool
   */
  public function __isset(string $name): bool {
    return isset($this->data[$name]);
  }
}
