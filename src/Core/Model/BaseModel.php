<?php

namespace Snake\Core\Model;

use Snake\Core\Database\DB;

class BaseModel {
  /**
   * The table name
   * 
   * @var string
   */
  protected static $table = 'users';

  /**
   * The primary key
   * 
   * @var string
   */
  protected static $primaryKey = 'id';

  /**
   * Create a new instance
   * 
   * @return void
   */
  public function __construct() {
    $this->table = static::$table;
    $this->primaryKey = static::$primaryKey;
  }

  /**
   * Handle method calls
   * 
   * @param string $method
   * @param array $args
   * 
   * @return object
   */
  public static function query($sql, $params = []) {
    return DB::query($sql, $params);
  }

  /**
   * Get all data from a table
   * 
   * @param string $table
   * 
   * @return object
   */
  public static function all() {
    return DB::all(static::$table);
  }

  /**
   * Get data from a table
   * 
   * @param array $where
   * 
   * @return object
   */
  public static function get(array $where = []) {
    return DB::get(static::$table, $where);
  }

  /**
   * Find a record
   * 
   * @param string $id
   * @param array $where
   * 
   * @return object
   */
  public static function find(string $id, array $where = []) {
    return DB::get(static::$table, array_merge($where, [static::$primaryKey => $id]));
  }

  /**
   * Insert a record
   * 
   * @param array $data
   * 
   * @return object
   */
  public static function insert(array $data = []) {
    return DB::insert(static::$table, $data);
  }

  /**
   * Update a record
   * 
   * @param string $id
   * @param array $data
   * @param array $where
   * 
   * @return object
   */
  public static function update(string $id, array $data = [], array $where = []) {
    return DB::update(static::$table, array_merge($data, [static::$primaryKey => $id]), $where);
  }

  /**
   * Delete a record
   * 
   * @param string $id
   * @param array $where
   * 
   * @return object
   */
  public static function delete(string $id, array $where = []) {
    return DB::delete(static::$table, array_merge($where, [static::$primaryKey => $id]));
  }

  /**
   * Get the table name
   * 
   * @return string
   */
  public static function getTable() {
    return static::$table;
  }
}