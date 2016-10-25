<?php
namespace Types\Value;

class Value{
  private $value;
  private $type;

  public function __get($name){
    switch($name){
      case "value":
        return $this->value;
      case "type":
        return $this->type;
    }
  }

  public function isReference(){
    return $this->type == "Reference";
  }

  public function isUndefined(){
    return $this->type == "Undefined";
  }

  public function isNull(){
    return $this->type == "Null";
  }

  public function __construct(string $type, $value = null){
    $this->type = $type;
    $this->value = $value;
  }

  public function ToPrimetiv($input = "Number", $preferredtype = null){
    if($this->isObject()){
      return $this->value->DefaultValue($preferredtype);
    }
    return $this;
  }

  public function isBoolean(){
    return $this->type == "Boolean";
  }

  public function ToBoolean(){
    if($this->isBoolean())
      return $this->value;
    if($this->isNull())
      return false;
    if($this->isUndefined())
      return false;
    if($this->isNumber())
      return $this->value != 0;
    if($this->isString())
      return strlen($this->value) != 0;
    if($this->isObject())
      return true;
  }

  public function isNumber(){
    return $this->type == "Number";
  }

  public function ToNumber(){
    if($this->isUndefined())
      return acos(8);//this will return in NaN
    if($this->isNull())
      return +0;
    if($this->isBoolean())
      return $this->value ? 1 : +0;
    if($this->isNumber())
      return $this->value;
    if($this->isString())
      return $this->stringToNumber($this->value);
    if($this->isObject())
      return $this->ToPrimetiv($this->value, "Number")->ToNumber();
  }

  public function isString(){
    return $this->type == "String";
  }

  public function ToString(){
    if($this->isUndefined())
      return "undefined";
    if($this->isNull())
      return "Null";
    if($this->isBoolean())
      return $this->value ? "true" : "false";
    if($this->isNumber())
      return $this->numberToString();
    if($this->isString())
      return $this->value;
    if($this->isObject())
      return $this->ToPrimetiv($this->value, "String")->ToString();
    exit($this->type."<-ToString");
  }

  public function isObject(){
   return $this->type == "Object";
  }

  public function ToObject(){
    if($this->isObject())
      return $this->value;
    if($this->isUndefined())
      throw new \RuntimeException("Cant convert Undefined to object");
    exit("Make Value->ToObject('".$this->type."')!!");
  }

  private function numberToString() : string{
    if(is_nan($this->value))
     return "NaN";

    if($this->value === +0)
      return "0";

    if(is_infinite($this->value))
      return "Infinity";

    return strval($this->value);
  }
}