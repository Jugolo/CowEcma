<?php
namespace Expresion\LogicalExpresion;

use Expresion\BaseExpresion\BaseExpresion;
use Types\Value\Value;
use Expresion\ExpresionResult\ExpresionResult;

class LogicalExpresion implements BaseExpresion{
  private $arg1;
  private $arg2;
  private $arg;

  public function __construct(BaseExpresion $arg1, string $arg, BaseExpresion $arg2){
    $this->arg1 = $arg1;
    $this->arg  = $arg;
    $this->arg2 = $arg2;
  }

  public function parse(\Ecma\Ecma $ecma) : ExpresionResult{
    if($this->arg == "&&"){
      if(!($v = $this->arg1->parse($ecma)->GetValue())->ToBoolean())
       return new ExpresionResult(new Value("Boolean", false));
      return $this->arg2->parse($ecma);
    }elseif($this->arg == "||"){
      if(($v = $this->arg1->parse($ecma))->GetValue()->ToBoolean())
        return $v;
      return $this->arg2->parse($ecma);
    }
  }
}