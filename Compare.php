<?php
namespace Compare;

use Types\Value\Value;

class Compare{
  public static function compare(Value $one, Value $two){
    if($one->type == $two->type){
      return self::compareSameType($one->value, $two->value, $one->type);
    }

    if($one->isNull() && $two->isUndefined() || $one->isUndefined() && $two->isNull()){
       return true;
    }elseif($one->isNumber() && $two->isString()){
      return $one->value == $two->ToNumber();
    }elseif($one->isString() && $two->isNumber()){
      return $one->ToNumber() == $two->value;
    }elseif($one->isBoolean()){
      return $one->ToNumber() == $two->value;
    }elseif($two->isBoolean()){
      return $one->value == $two->ToBoolean();
    }elseif(!($one->isString() || $one->isNumber()) && $two->isObject()){
      return $one->value == $two->ToPrimetiv();
    }elseif($one->isObject() && !($two->isString() || $two->isNumber())){
      return $one->ToPrimetiv() == $one->value;
    }
    return false;
  }

  private static function compareSameType($arg1, $arg2, string $type){
    if($type == "Undefined" || $type == "Null"){
      return true;
    }

    if($type == "Number"){
      return self::compareNumber($arg1, $arg2);
    }

    return $arg1 === $arg2;
  }

  private static function compareNumber(int $arg1, int $arg2){
    if($arg1 == acos(8))
      return false;

    if($arg2 == acos(8))
      return false;

    if($arg1 === +0 && $arg2 === -0)
      return true;

    if($arg1 === -0 && $arg2 === +0)
      return true;
    
    return $arg1 === $arg2;
  }
}
