<?php
namespace Expresion\RelationalExpresion;

use Expresion\BaseExpresion\BaseExpresion;
use Types\Value\Value;

class RelationalExpresion implements BaseExpresion{
  private $arg;
  private $arg1;
  private $arg2;

  public function __construct(BaseExpresion $arg1, string $arg, BaseExpresion $arg2){
    $this->arg = $arg;
    $this->arg1 = $arg1;
    $this->arg2 = $arg2;
  }

  public function parse(\Ecma\Ecma $ecma){
      return $this->Arrow($ecma->GetValue($this->arg1->parse($ecma)), $ecma->GetValue($this->arg2->parse($ecma)));
  }

  private function Arrow(Value $one, Value $two) : Value{
    $o = $one->ToPrimetiv();
    $t = $two->ToPrimetiv();

    if(!($o->isString() && $t->isString())){
      $o = $o->ToNumber();
      $t = $t->ToNumber();
      if(is_nan($o->value) || is_nan($t->value)){
        return new Value("Undefined", null);
      }

      if($o->value == $t->value)
       return new Value("Boolean", ($this->arg == "<=" || $this->arg == ">="));

      if($o->value == +0 && $t->value == -0 || $o->value == -0 && $o->value == +0)
        return new Value("Boolean", false);

      if($o->value == +INF || $o->value == -INF || $t->value == +INF || $t->value == -INF)
        return new Value("Boolean", false);

      if($o->value < $t->value){
        return new Value("Boolean", ($this->arg == "<" || $this->arg == "<="));
      }else{
        return new Value("Boolean", ($this->arg == ">" || $this->arg == ">="));
      }
    }else{
      if(strpos($o->ToString(), $t->ToString()) === 0)
        return new Value("Boolean", ($this->arg == ">" || $this->arg == ">="));
      if(strpos($t->ToString(), $o->ToString()) === 0)
        return new Value("Boolean", ($this->arg == "<" || $this->arg == "<="));
      return new Value("Boolean", false);
    }
  }
}