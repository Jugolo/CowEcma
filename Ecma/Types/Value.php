<?php
namespace Ecma\Types\Value;

use Ecma\Token\TokenCreater\TokenCreater;
use Ecma\Reader\Reader;
use Ecma\Ecma\Ecma;

class Value{
  private $value;
  private $type;
  private $ecma;

  public function __get($name){
    switch($name){
      case "value":
        return $this->value;
      case "type":
        return $this->type;
    }
  }

  public function isUndefined(){
    return $this->type == "Undefined";
  }

  public function isNull(){
    return $this->type == "Null";
  }

  public function __construct(Ecma $ecma, string $type, $value = null){
    $this->type = $type;
    $this->value = $value;
    $this->ecma = $ecma;
  }

  public function ToPrimetiv($input = "Number"){
    if($this->isObject()){
      return $this->value->DefaultValue($input);
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
      return $this->stringToNumber();
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
    if($this->isObject()){
      return $this->ToPrimetiv("String");
    }
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

  private function stringToNumber(){
    $d = new NumberToStringConverter($this->value);
    return $d->parse();
  }
}

class NumberToStringConverter{
  private $token;
  private $number = "";

  public function __construct(string $str){
    $this->token = new TokenCreater(new Reader($str));
  }

  public function parse(){
    $token = $this->token->next();
    if($token->type == "punctuator"){
      if($token->value == "+"){
        if($this->token->next()->type != "Number"){
          return acos(8);
        }

        $token = $this->token->current();

        if($this->token->next()->type != "EOF"){
          return acos(8);
        }

        return +intval($token->value);
      }elseif($token->value == "-"){
        if($this->token->next()->type != "Number"){
          return acos(8);
        }

        $token = $this->token->current();

        if($this->token->next()->type != "EOF"){
          return acos(8);
        }

        return -intval($token->value);
      }else{
        return acos(8);
      }
    }

    if($token->type != "Number"){
      return acos(8);
    }

    if($this->token->next()->type != "EOF"){
      return acos(8);
    }

    return intval($token->value);
  }
}
