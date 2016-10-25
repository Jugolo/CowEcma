<?php
namespace Expresion\EqualityExpresion;

use Expresion\BaseExpresion\BaseExpresion;
use Types\Value\Value;
use Expresion\ExpresionResult\ExpresionResult;

class EqualityExpresion implements BaseExpresion{
  private $arg;
  private $arg1;
  private $arg2;

  public function __construct(BaseExpresion $arg1, string $arg, BaseExpresion $arg2){
    $this->arg = $arg;
    $this->arg1 = $arg1;
    $this->arg2 = $arg2;
  }

  public function parse(\Ecma\Ecma $ecma) : ExpresionResult{
    $result =  \Compare\Compare::compare($this->arg1->parse($ecma)->GetValue(), $this->arg2->parse($ecma)->GetValue());
    return new ExpresionResult(new Value("Boolean", $this->arg == "==" ? $result : !$result));
  }
}