<?php

namespace App\Models;

use PDO;
use Exception;

class Model
{
  /** @var string Table name for the model */
  protected $table;

  /** @var string Primary key for the model */
  protected $primary_key = 'id';

  /** @var PDO Database connection */
  protected static $connection;

  /** @var array Current query conditions */
  protected $conditions = [];

  /** @var array Current query bindings */
  protected $bindings = [];

  /** @var array Eager loaded relationships */
  protected $with = [];

  /** @var array Fields that can be mass assigned */
  protected $fillable = [];

  /** @var array Fields that are hidden from array/JSON conversion */
  protected $hidden = [];

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
    try {
      $statement = static::$connection->prepare(
        "SELECT * FROM {$this->table} WHERE {$this->primary_key} = :id"
      );
      $statement->execute(['id' => $id]);
      $result = $statement->fetchObject(static::class);
      if($result) {
        $this->loadEagerRelations($result);
      }
      return $result;
    } catch (Exception $e) {
      throw new Exception("Error finding record: " . $e->getMessage());
    }
  }

  public function get()
  {
    try {
      $query = "SELECT * FROM {$this->table}";
      if (!empty($this->conditions)) {
        $query .= ' WHERE ' . implode(' AND ', $this->conditions);
      }
      $statement = static::$connection->prepare($query);
      $statement->execute($this->bindings);
      $results = $statement->fetchAll(PDO::FETCH_CLASS, static::class);
      foreach ($results as $result) {
        $this->loadEagerRelations($result);
      }
      return $results;
    } catch (Exception $e) {
      throw new Exception("Error getting records: " . $e->getMessage());
    }
  }

  public function create(array $data)
  {
    try{
      $keys = implode(', ', array_keys($data));
      $values = ':' . implode(', :', array_keys($data));
      $statement = static::$connection->prepare("INSERT INTO {$this->table} ($keys) VALUES ($values)");
      $statement->execute($data);
      return static::find(static::$connection->lastInsertId());
    }catch(Exception $e){
      throw new Exception("Error creating record: " . $e->getMessage());
    }
  }

  public function update(array $data)
  {
    try{
      $keys = array_map(function ($key) {
      return "$key = :$key";
      }, array_keys($data));
      $keys = implode(', ', $keys);
      $statement = static::$connection->prepare("UPDATE {$this->table} SET $keys WHERE {$this->primary_key} = :id");
      $statement->execute(array_merge($data, ['id' => $this->{$this->primary_key}]));
      return $this;
    }catch(Exception $e){
      throw new Exception("Error updating record: " . $e->getMessage());
    }
  }

  // where method
  public function where($params)
  {
    try {
      $operator = '=';
      $column = $value = '';
      $param_lists = func_get_args();
      if(count($param_lists) > 2 && count($param_lists) === 3) {
        [$column, $operator, $value] = $param_lists;
      }else{
        [$column, $value] = $param_lists;
      }
      $binding_keys = str_replace('.', '_', $column);
      $this->conditions[] = "$column $operator :$binding_keys";
      $this->bindings[$binding_keys] = $value;
      // $statement = static::$connection->prepare("SELECT * FROM {$this->table} WHERE $column $operator :value");
      // $statement->execute(['value' => $value]);
      return $this;
    } catch (Exception $e) {
      throw new Exception("Error applying conditions: " . $e->getMessage());
    }
  }

  // first method
  public function first()
  {
    try {
      $query = "SELECT * FROM {$this->table}";
      if (!empty($this->conditions)) {
        $query .= ' WHERE ' . implode(' AND ', $this->conditions);
      }
      $query .= " LIMIT 1";
      $statement = static::$connection->prepare($query);
      $statement->execute($this->bindings);
      $result = $statement->fetchObject(static::class);
      if ($result) {
        $this->loadEagerRelations($result);
      }
      return $result;

    } catch (Exception $e) {
      throw new Exception("Error getting first record: " . $e->getMessage());
    }
  }

  // delete method
  public function delete()
  {
    try {
      $query = "DELETE FROM {$this->table} WHERE {$this->primary_key} = :id";
      $statement = static::$connection->prepare($query);
      $statement->execute(['id' => $this->{$this->primary_key}]);
      return true;
    } catch (Exception $e) {
      throw new Exception("Error deleting record: " . $e->getMessage());
    } 
  }
  // with method
  public function with($relations)
  {
    $this->with[] = is_string($relations) ? func_get_args() : $relations;
    return $this;
  }
  public function belongsTo($related, $foreignKey, $ownerKey = 'id')
  {
    try {
      $related_instance = new $related;
      // $query = "SELECT * FROM {$related_instance->table} WHERE {$foreignKey} = ?";
       $query = "SELECT * FROM {$related_instance->table} WHERE {$ownerKey} = :value";
      $stmt = static::$connection->prepare($query);
      $stmt->execute([ 'value' => $this->{$foreignKey}]);
      return $stmt->fetchObject($related);
    } catch (Exception $e) {
      throw new Exception("Error getting related record: " . $e->getMessage());
    }
  }

  public function hasMany($related, $foreignKey, $localKey = 'id')
  {
    $related_instance = new $related;
    // $query = "SELECT * FROM {$related_instance->table} WHERE {$foreignKey} = ?";
    $query = "SELECT * FROM {$related_instance->table} WHERE {$foreignKey} = :value";
    $stmt = static::$connection->prepare($query);
    $stmt->execute([ 'value' => $this->{$localKey}]);
    return $stmt->fetchAll(PDO::FETCH_CLASS, $related);
  }


  // load eager relationships
  protected function loadEagerRelations($model)
  {
    foreach ($this->with as $relation) {
      if (method_exists($model, $relation)) {
        $model->{$relation} = $model->{$relation}();
      }
    }
  }
}
