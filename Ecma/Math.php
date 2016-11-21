<?php
namespace Ecma\Math;

use Ecma\Types\Value\Value;

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

  public static function ordutf8l($str, $index){
    $char = mb_substr($str, $index, 1, 'UTF-8');

    if (mb_check_encoding($char, 'UTF-8')) {
        $ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
        return hexdec(bin2hex($ret));
    } else {
        return null;
    }
  }

  public static function unichr($codes) {
    if (is_scalar($codes)) $codes= func_get_args();
    $str= '';
    foreach ($codes as $code) $str.= html_entity_decode('&#'.$code.';',ENT_NOQUOTES,'UTF-8');
    return $str;
  }
}
