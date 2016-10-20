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
    $this->arg  = $arg;
    $this->arg2 = $arg2;
  }

  public function parse(\Ecma\Ecma $ecma){
    if($this->arg == "&&"){
      if(!($v = $ecma->GetValue($this->arg1->parse($ecma)))->ToBoolean())
       return new Value("Boolean", false);
      return $ecma->GetValue($this->arg2->parse($ecma));
    }elseif($this->arg == "||"){
      if(($v = $ecma->GetValue($this->arg1->parse($ecma)))->ToBoolean())
        return $v;
      return $ecma->GetValue($this->arg2->parse($ecma));
    }
  }
}
