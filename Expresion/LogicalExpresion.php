<?php
namespace Expresion\LogicalExpresion;

use Expresion\BaseExpresion\BaseExpresion;
use Types\Value\Value;

class LogicalExpresion implements BaseExpresion{
  private $arg1;
  private $arg2;
  private $arg;

  public function __construct(BaseExpresion $arg1, string $arg, BaseExpresion $arg2){
    $this->arg1 = $arg1;
    $þhis->arg = $arg;
    $this->arg2 = $arg2;
  }

  public function parse(\Ecma\Ecma $ecma){
    if($this->arg == "&&"){
      if(!($v = $this->arg1->parse($ecma)->GetValue())->ToBoolean())
       return $v;
      return $this->arg2->parse($ecma)->GetValue();
    }elseif($this->arg == "||"){
      if(($v = $this->arg1->parse($ecma)->GetValue())->ToBoolean())
        return $v;
      return $this->arg2->parse($ecma)->GetValue();
    }
  }
}
