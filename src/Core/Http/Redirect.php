<?php

namespace Snake\Core\Http;

class Redirect {
  /**
   * Redirect to a given path
   *
   * @param string $path
   *
   * @return void
   */
  public static function to(string $path): void {
    header('Location: ' . $path);
    exit();
  }

  /**
   * Redirect to a given path with a message
   *
   * @param string $path
   * @param string $message
   *
   * @return void
   */
  public static function with(string $path, string $message = ''): void {
    $_SESSION['message'] = $message;
    header('Location: ' . $path);
    exit();
  }

  /**
   * Redirect to a given path with a message and data
   *
   * @param string $path
   * @param string $message
   * @param array $data
   *
   * @return void
   */
  public static function withData(string $path, string $message = '', array $data = []): void {
    $_SESSION['message'] = $message;
    $_SESSION['data'] = $data;
    header('Location: ' . $path);
    exit();
  }

  /**
   * Redirect to a given path with a message and errors
   *
   * @param string $path
   * @param string $message
   * @param array $errors
   *
   * @return void
   */
  public static function withErrors(string $path, string $message = '', array $errors = []): void {
    $_SESSION['message'] = $message;
    $_SESSION['errors'] = $errors;
    header('Location: ' . $path);
    exit();
  }

  /**
   * Redirect to a given path with a message, data and errors
   *
   * @param string $path
   * @param string $message
   * @param array $data
   * @param array $errors
   *
   * @return void
   */
  public static function withDataAndErrors(string $path, string $message = '', array $data = [], array $errors = []): void {
    $_SESSION['message'] = $message;
    $_SESSION['data'] = $data;
    $_SESSION['errors'] = $errors;
    header('Location: ' . $path);
    exit();
  }
}