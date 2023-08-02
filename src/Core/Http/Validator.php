<?php

namespace Snake\Core\Http;

class Validator {
  /*
  * Check if a given input is valid
  *
  * @param string $type
  * @param string $item
  * @param array $rules
  *
  * @return bool
  */
  public static function isValid(string $type = '', string $item = '', array $rules = []) {
    switch ($type) {
      case 'post':
        return self::validate($_POST, $item, $rules);
        break;
      case 'get':
        return self::validate($_GET, $item, $rules);
        break;
      default:
        return false;
        break;
    }
  }

  /*
  * Validate a given input
  *
  * @param array $source
  * @param string $item
  * @param array $rules
  *
  * @return bool
  */
  private static function validate(array $source = [], string $item = '', array $rules = []) {
    foreach ($rules as $rule => $rule_value) {
      switch ($rule) {
        case 'required':
          if (empty($source[$item]) && $rule_value) {
            return false;
          }
          break;
        case 'min':
          if (strlen($source[$item]) < $rule_value) {
            return false;
          }
          break;
        case 'max':
          if (strlen($source[$item]) > $rule_value) {
            return false;
          }
          break;
        case 'matches':
          if ($source[$item] != $source[$rule_value]) {
            return false;
          }
          break;
        case 'unique':
          $model = new $rule_value;
          $field = $item;
          $value = $source[$item];
          $result = $model->where($field, $value)->get();
          if ($result) {
            return false;
          }
          break;
        default:
          break;
      }
    }

    return true;
  }
}