<?php
namespace Expresion\BitwiseExpresion;

use Expresion\BaseExpresion\BaseExpresion;
use Expresion\ExpresionResult\ExpresionResult;

class BitwiseExpresion implements BaseExpresion{
  private $arg;
  private $arg1;
  private $arg2;

  public function __construct(BaseExpresion $arg1, string $arg, BaseExpresion $arg2){
    $this->arg = $arg;
    $this->arg1 = $arg1;
    $this->arg2 = $arg2;
  }

  public function parse(\Ecma\Ecma $ecma) : ExpresionResult{
    $left = $this->arg1->parse($ecma)->GetValue();
    $right = $this->arg2->parse($ecma)->GetValue();
    if($this->arg == "+" && ($left->isString() || $right->isString()))
      return new ExpresionResult(new \Types\Value\Value("String", $left->ToString().$right->ToString()));
    return new ExpresionResult(\Math\Math::math($left, $this->arg, $right));
  }
}