<?php
namespace Types\Objects\Property;

use Types\Value\Value;

class Property{
  private $value;
  private $attribute;

  public function __construct(Value $value, array $attribute = []){
     $this->value = $value;
     $this->attribute = $attribute;
  }

  public function getValue() : Value{
    return $this->value;
  }

  public function __get($arg){
    switch($arg){
      case "value":
        return $this->value;
    }

    return null;
  }

  public function hasAttribute(string $key){
    return in_array($key, $this->attribute);
  }
}
