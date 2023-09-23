<?php

namespace Snake\Core\Database;

use PDO;
use PDOException;
use Snake\Interface\Database\IBuilder;

class Builder implements IBuilder {
  /**
   * Object instance
   *
   * @var object $pdo
   */
  private $pdo;

  /**
   * Create a new instance
   *
   * @return void
   *
   * @throws PDOException
   */
  public function __construct() {
    $table = config('database', 'default');

    if ($this->pdo === null) {
      try {
        if (config('database', 'default') === 'mysql') {
          $this->pdo = new PDO(
            'mysql:host=' . config('database', 'connections')[$table]['host'] . ';dbname=' . config('database', 'connections')[$table]['name'] . ';charset=utf8',
            config('database', 'connections')[$table]['username'],
            config('database', 'connections')[$table]['password']
          );
        } else if (config('database', 'default') === 'sqlite') {
          $this->pdo = new PDO(
            'sqlite:' . config('database', 'connections')[$table]['name']
          );
        } else if (config('database', 'default') === 'pgsql') {
          $this->pdo = new PDO(
            'pgsql:host=' . config('database', 'connections')[$table]['host'] . ';port=' . config('database', 'connections')[$table]['port'] . ';dbname=' . config('database', 'connections')[$table]['name'] . ';user=' . config('database', 'connections')[$table]['username'] . ';password=' . config('database', 'connections')[$table]['password']
          );
        } else if (config('database', 'default') === 'sqlsrv') {
          $this->pdo = new PDO(
            'sqlsrv:Server=' . config('database', 'connections')[$table]['host'] . ';Database=' . config('database', 'connections')[$table]['name'] . ';ConnectionPooling=0',
            config('database', 'connections')[$table]['username'],
            config('database', 'connections')[$table]['password']
          );
        }
      } catch (PDOException $e) {
        die($e->getMessage());
      }
    }
  }

  /**
   * Get a PDO instance
   *
   * @return object
   */
  public function pdo(): object {
    return $this->pdo;
  }

  /**
   * Handle database query
   *
   * @param string $sql
   * @param array $params
   *
   * @return object
   */
  public function query(string $sql, array $params = []): object {
    if (count($params) > 0) {
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($params);

      return $stmt;
    } else {
      return $this->pdo->query($sql);
    }
  }

  /**
   * Get all records
   *
   * @param string $table
   *
   * @return object
   */
  public function all(string $table): object {
    $stmt = $this->pdo->prepare('SELECT * FROM ' . $table);
    $stmt->execute();

    return $stmt;
  }

  /**
   * Get a record
   *
   * @param string $table
   * @param array $where
   *
   * @return object
   */
  public function get(string $table, array $where = []): object {
    if (count($where) > 0) {
      $sql = 'SELECT * FROM ' . $table . ' WHERE ';
      $i = 0;

      foreach ($where as $key => $value) {
        $i++;
        $sql .= $key . ' = :' . $key;

        if ($i < count($where)) {
          $sql .= ' AND ';
        }
      }

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($where);

      return $stmt;
    } else {
      return $this->all($table);
    }
  }

  /**
   * Insert a record
   *
   * @param string $table
   * @param array $data
   *
   * @return object
   */
  public function insert(string $table, array $data = []): object {
    if (count($data) > 0) {
      $sql = 'INSERT INTO ' . $table . ' (';
      $i = 0;

      foreach ($data as $key => $value) {
        $i++;
        $sql .= $key;

        if ($i < count($data)) {
          $sql .= ', ';
        }
      }

      $sql .= ') VALUES (';
      $i = 0;

      foreach ($data as $key => $value) {
        $i++;
        $sql .= ':' . $key;

        if ($i < count($data)) {
          $sql .= ', ';
        }
      }

      $sql .= ')';

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($data);

      return $stmt;
    } else {
      return false;
    }
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
  public function update(string $table, array $data = [], array $where = []): object {
    if (count($data) > 0) {
      $sql = 'UPDATE ' . $table . ' SET ';
      $i = 0;

      foreach ($data as $key => $value) {
        $i++;
        $sql .= $key . ' = :' . $key;

        if ($i < count($data)) {
          $sql .= ', ';
        }
      }

      $sql .= ' WHERE ';
      $i = 0;

      foreach ($where as $key => $value) {
        $i++;
        $sql .= $key . ' = :' . $key;

        if ($i < count($where)) {
          $sql .= ' AND ';
        }
      }

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute(array_merge($data, $where));

      return $stmt;
    } else {
      return false;
    }
  }

  /**
   * Delete a record
   *
   * @param string $table
   * @param array $where
   *
   * @return object
   */
  public function delete(string $table, array $where = []): object {
    if (count($where) > 0) {
      $sql = 'DELETE FROM ' . $table . ' WHERE ';
      $i = 0;

      foreach ($where as $key => $value) {
        $i++;
        $sql .= $key . ' = :' . $key;

        if ($i < count($where)) {
          $sql .= ' AND ';
        }
      }

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($where);

      return $stmt;
    } else {
      return false;
    }
  }

  /**
   * Get the last inserted ID
   *
   * @return string
   */
  public function lastInsertId(): string {
    return $this->pdo->lastInsertId();
  }
}
