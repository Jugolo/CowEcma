<?php
namespace Ecma\Types\Objects\Property;

use Ecma\Types\Value\Value;

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

  public function hasAttribute(string $name){
    return in_array($name, $this->attribute);
  }

  public function __get($arg){
    switch($arg){
      case "value":
        return $this->value;
    }

    return null;
  }
}
