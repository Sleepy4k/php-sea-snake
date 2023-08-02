<?php

namespace Snake\Core\Http;

class Input {
  /*
  * Check if a given input exists
  *
  * @param string $type
  *
  * @return bool
  */
  public static function exists(string $type = '') {
    switch ($type) {
      case 'post':
        return (!empty($_POST)) ? true : false;
        break;
      case 'get':
        return (!empty($_GET)) ? true : false;
        break;
      default:
        return false;
        break;
    }
  }

  /*
  * Get a given input
  *
  * @param string $item
  *
  * @return string
  */
  public static function get(string $item = '') {
    if (isset($_POST[$item])) {
      return $_POST[$item];
    } else if (isset($_GET[$item])) {
      return $_GET[$item];
    }

    return '';
  }
  
  /*
  * Get all inputs
  *
  * @return array
  */
  public static function all() {
    return array_merge($_POST, $_GET);
  }

  /*
  * Get a given input
  *
  * @param string $item
  *
  * @return string
  */
  public static function old(string $item = '') {
    if (isset($_SESSION['old'][$item])) {
      return $_SESSION['old'][$item];
    }

    return '';
  }

  /*
  * Set a given input
  *
  * @param string $item
  * @param string $value
  *
  * @return void
  */
  public static function set(string $item = '', string $value = '') {
    $_SESSION['old'][$item] = $value;
  }

  /*
  * Delete a given input
  *
  * @param string $item
  *
  * @return void
  */
  public static function delete(string $item = '') {
    unset($_SESSION['old'][$item]);
  }
}