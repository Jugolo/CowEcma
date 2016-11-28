<?php
namespace Ecma\Expresion\BitwiseExpresion;

use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Expresion\ExpresionResult\ExpresionResult;
use Ecma\Ecma\Ecma;
use Ecma\Types\Value\Value;
use Ecma\Math\Math;

class BitwiseExpresion implements BaseExpresion{
  private $arg;
  private $arg1;
  private $arg2;

  public function __construct(BaseExpresion $arg1, string $arg, BaseExpresion $arg2){
    $this->arg = $arg;
    $this->arg1 = $arg1;
    $this->arg2 = $arg2;
  }

  public function parse(Ecma $ecma) : ExpresionResult{
    $left = $this->arg1->parse($ecma)->GetValue();
    $right = $this->arg2->parse($ecma)->GetValue();
    if($this->arg == "+" && ($left->isString() || $right->isString()))
      return new ExpresionResult(new Value($ecma, "String", $left->ToString().$right->ToString()));
    return new ExpresionResult(Math::math($left, $this->arg, $right));
  }
}
