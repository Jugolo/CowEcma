<?php
namespace Ecma\Expresion\EqualityExpresion;

use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Types\Value\Value;
use Ecma\Expresion\ExpresionResult\ExpresionResult;
use Ecma\Ecma\Ecma;
use Ecma\Compare\Compare;

class EqualityExpresion implements BaseExpresion{
  private $arg;
  private $arg1;
  private $arg2;

  public function __construct(BaseExpresion $arg1, string $arg, BaseExpresion $arg2){
    $this->arg = $arg;
    $this->arg1 = $arg1;
    $this->arg2 = $arg2;
  }

  public function parse(Ecma $ecma) : ExpresionResult{
    $result =  Compare::compare($this->arg1->parse($ecma)->GetValue(), $this->arg2->parse($ecma)->GetValue());
    return new ExpresionResult(new Value($ecma, "Boolean", $this->arg == "==" ? $result : !$result));
  }
}
