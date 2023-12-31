<?php

namespace Snake\Core\Database;

use Snake\Core\Facade\App;
use Snake\Interface\Database\IDB;

final class DB implements IDB {
  /**
   * Query the database
   * 
   * @param string $sql
   * @param array $params
   * 
   * @return object
   */
  public static function query(string $sql, array $params = []): object {
    return static::builder()->query($sql, $params);
  }

  /**
   * Get all data from a table
   * 
   * @param string $table
   * 
   * @return object
   */
  public static function all(string $table): object {
    return static::builder()->all($table);
  }

  /**
   * Get data from a table
   * 
   * @param string $table
   * @param array $where
   * 
   * @return object
   */
  public static function get(string $table, array $where = []): object {
    return static::builder()->get($table, $where);
  }

  /**
   * Insert a record
   * 
   * @param string $table
   * @param array $data
   * 
   * @return object
   */
  public static function insert(string $table, array $data = []): object {
    return static::builder()->insert($table, $data);
  }

  /**
   * Update a record
   * 
   * @param string $table
   * @param array $data
   * @param array $where
   * 
   * @return object
   */
  public static function update(string $table, array $data = [], array $where = []): object {
    return static::builder()->update($table, $data, $where);
  }

  /**
   * Delete a record
   * 
   * @param string $table
   * @param array $where
   * 
   * @return object
   */
  public static function delete(string $table, array $where = []): object {
    return static::builder()->delete($table, $where);
  }

  /**
   * Get router instance
   *
   * @return Builder
   */
  public static function builder(): Builder {
    return App::get()->singleton(Builder::class);
  }
}