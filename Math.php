<?php
namespace Math;

use Types\Value\Value;

class Math{
  public static function math(Value $first, string $arg, Value $second) : Value{
    switch($arg){
      case "*":
        return new Value("Number", $first->ToNumber() * $second->ToNumber());
      case "/":
        return new Value("Number", $first->ToNumber() / $second->ToNumber());
      case "%":
        return new Value("Number", $first->ToNumber() % $second->ToNumber());
      case "+":
        return new Value("Number", $first->ToNumber() + $second->ToNumber());
      case "-":
        return new Value("Number", $first->ToNumber() - $second->ToNumber());
      case "<<":
        return new Value("Number", $first->ToNumber() << $second->ToNumber());
      case ">>":
        return new Value("Number", $first->ToNumber() >> $second->ToNumber());
      case ">>>":
        return new Value("Number", $first->ToNumber() >> ($second->ToNumber() & 0x1F));
      case "&":
        return new Value("Number", $first->ToNumber() & $second->ToNumber());
      case "^":
        return new Value("Number", $first->ToNumber() ^ $second->ToNumber());
      case "|":
        return new Value("Number", $first->ToNumber() | $second->ToNumber());
    }
  }
}
