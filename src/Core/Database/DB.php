<?php

namespace Snake\Core\Database;

use PDO;
use PDOException;
use Snake\Core\Support\Config;

class DB {
  /*
  * Object instance
  *
  * @var object $pdo
  */
  private $pdo;

  /*
  * Create a new instance
  *
  * @return void
  *
  * @throws PDOException
  */
  public function __construct() {
    if ($this->pdo === null) {
      try {
        $this->pdo = new PDO(
          'mysql:host=' . Config::get('database/hostname') . ';dbname=' . Config::get('database/name'),
          Config::get('database/username'),
          Config::get('database/password')
        );
      } catch (PDOException $e) {
        die($e->getMessage());
      }
    }
  }

  /*
  * Get a PDO instance
  *
  * @return object
  */
  public function pdo() {
    return $this->pdo;
  }

  /*
  * Handle database query
  *
  * @param string $sql
  * @param array $params
  *
  * @return object
  */
  public function query(string $sql = '', array $params = []) {
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt;
  }

  /*
  * Get all records
  *
  * @param string $table
  *
  * @return object
  */
  public function all(string $table = '') {
    $stmt = $this->pdo->prepare('SELECT * FROM ' . $table);
    $stmt->execute();

    return $stmt;
  }

  /*
  * Get a record
  *
  * @param string $table
  * @param array $where
  *
  * @return object
  */
  public function get(string $table = '', array $where = []) {
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
  }

  /*
  * Insert a record
  *
  * @param string $table
  * @param array $data
  *
  * @return object
  */
  public function insert(string $table = '', array $data = []) {
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
  }

  /*
  * Update a record
  *
  * @param string $table
  * @param array $data
  * @param array $where
  *
  * @return object
  */
  public function update(string $table = '', array $data = [], array $where = []) {
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
  }

  /*
  * Delete a record
  *
  * @param string $table
  * @param array $where
  *
  * @return object
  */
  public function delete(string $table = '', array $where = []) {
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
  }

  /*
  * Get the last inserted ID
  *
  * @return string
  */
  public function lastInsertId() {
    return $this->pdo->lastInsertId();
  }
}