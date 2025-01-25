<?php
namespace App\Core;

use App\Core\Session;

class Validator 
{
  protected $errors = [];

  public function validate(array $data, array $rules) 
  {
    foreach ($rules as $field => $rule_set) {
      $rules_array = explode('|', $rule_set);
      foreach ($rules_array as $rule) {
        $this->ApplyRule($data, $field, $rule);
      }
    }
    if(!empty($this->errors)) {
      Session::set('errors', $this->errors);
      return false;
    }
    return true;
  }

  protected function ApplyRule(array $data, $field, $rule) 
  {
    if (strpos($rule, ':') !== false) {
      [$rule_name, $param] = explode(':', $rule);
    }
    if (!method_exists($this, $rule_name)) {
      throw new \Exception("Rule {$rule_name} is not defined");
    }

    $this->{$rule_name}($data, $field, $param ?? null);
  }

  // required rule
  protected function required(array $data, $field, $param = null) {
    if (!isset($data[$field]) || empty($data[$field])) {
      $this->errors[$field] = "The {$field} field is required";
    }
  }

  // email rule
  protected function email(array $data, $field, $param = null) {
    if (!filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
      $this->errors[$field] = "The {$field} field must be a valid email address";
    }
  }

  // min rule
  protected function min(array $data, $field, $param = null) {
    if (strlen($data[$field]) < $param) {
      $this->errors[$field] = "The {$field} field must be at least {$param} characters";
    }
  }

  // max rule
  protected function max(array $data, $field, $param = null) {
    if (strlen($data[$field]) > $param) {
      $this->errors[$field] = "The {$field} field must be at most {$param} characters";
    }
  }

  // unique rule
  protected function unique(array $data, $field, $param = null) {
    // TODO: implement unique rule
  }

  // confirm rule
  protected function confirm(array $data, $field, $param = null) {
    if ($data[$field] !== $data[$param]) {
      $this->errors[$field] = "The {$field} field must match {$param} field";
    }
  }

  // string rule
  protected function string(array $data, $field, $param = null) {
    // filter validation string
    if (!is_string($data[$field]) || empty($data[$field]) || trim($data[$field]) === '' || is_numeric($data[$field]) || is_bool($data[$field]) || is_array($data[$field]) || is_object($data[$field]) || is_null($data[$field]) || is_resource($data[$field]) || is_callable($data[$field]) || is_int($data[$field]) || is_float($data[$field]) || is_double($data[$field]) || is_long($data[$field])) {
      $this->errors[$field] = "The {$field} field must be a string";
    }
  }

  // numeric rule
  protected function numeric(array $data, $field, $param = null) {
    // filter validation numeric
    if (!is_numeric($data[$field])) {
      $this->errors[$field] = "The {$field} field must be numeric";
    }
  }

  public function errors() {
    return $this->errors;
  }
}
