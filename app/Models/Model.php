<?php
namespace App\Models;
use PDO;
class Model
{
  protected $table;
  protected $primary_key = 'id';
  protected static $connection;

  public static function setConnection(PDO $connection)
  {
    self::$connection = $connection;
  }

  public static function query()
  {
    return new static;
  }

  public function find($id)
  {
    $statement = static::$connection->prepare("SELECT * FROM {$this->table} WHERE {$this->primary_key} = :id");
    $statement->execute(['id' => $id]);
    return $statement->fetchObject(static::class);
  }

  public function all()
  {
    $statement = static::$connection->prepare("SELECT * FROM {$this->table}");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_CLASS, static::class);
  }

  public function create(array $data)
  {
    $keys = implode(', ', array_keys($data));
    $values = ':' . implode(', :', array_keys($data));
    $statement = static::$connection->prepare("INSERT INTO {$this->table} ($keys) VALUES ($values)");
    $statement->execute($data);
    return static::find(static::$connection->lastInsertId());
  }

  public function update(array $data)
  {
    $keys = array_map(function ($key) {
      return "$key = :$key";
    }, array_keys($data));
    $keys = implode(', ', $keys);
    $statement = static::$connection->prepare("UPDATE {$this->table} SET $keys WHERE {$this->primary_key} = :id");
    $statement->execute(array_merge($data, ['id' => $this->{$this->primary_key}]));
    return $this;
  }

  // where method
  public function where($column, $operator, $value)
  {
    $this->query = "SELECT * FROM {$this->table} WHERE {$column} {$operator} :value";
    $this->bindings[] = $value;
    return $this;
  }

  // delete method
  public function delete()
  {
    $statement = static::$connection->prepare("DELETE FROM {$this->table} WHERE {$this->primary_key} = :id");
    $statement->execute(['id' => $this->{$this->primary_key}]);
  }

  public function belongsTo($related, $foreignKey, $ownerKey = 'id')
    {
        $related_instance = new $related;
        $query = "SELECT * FROM {$related_instance->table} WHERE {$ownerKey} = ?";
        $stmt = static::$connection->prepare($query);
        $stmt->execute([$this->{$foreignKey}]);
        return $stmt->fetchObject($related);
    }

    public function hasMany($related, $foreignKey, $localKey = 'id')
    {
        $related_instance = new $related;
        $query = "SELECT * FROM {$related_instance->table} WHERE {$foreignKey} = ?";
        $stmt = static::$connection->prepare($query);
        $stmt->execute([$this->{$localKey}]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, $related);
    }
}