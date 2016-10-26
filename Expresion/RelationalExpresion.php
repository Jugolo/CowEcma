<?php
namespace Expresion\RelationalExpresion;

use Expresion\BaseExpresion\BaseExpresion;
use Expresion\ExpresionResult\ExpresionResult;
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

  public function parse(\Ecma\Ecma $ecma) : ExpresionResult{
      return new ExpresionResult($this->Arrow($this->arg1->parse($ecma)->GetValue(), $this->arg2->parse($ecma)->GetValue()));
  }

  private function Arrow(Value $one, Value $two) : Value{
    $o = $one->ToPrimetiv();
    $t = $two->ToPrimetiv();

    if(!($o->isString() && $t->isString())){
      $o = $o->ToNumber();
      $t = $t->ToNumber();
      if(is_nan($o) || is_nan($t)){
        return new Value("Undefined", null);
      }

      if($o == $t)
       return new Value("Boolean", ($this->arg == "<=" || $this->arg == ">="));

      /*if($o == +0 && $t == -0 || $o == -0 && $o == +0)
        return new Value("Boolean", false);*/

      if($o == +INF || $o == -INF || $t == +INF || $t == -INF)
        return new Value("Boolean", false);

      if($o < $t){
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