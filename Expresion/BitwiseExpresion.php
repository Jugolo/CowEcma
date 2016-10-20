<?php
namespace Expresion\BitwiseExpresion;

use Expresion\BaseExpresion\BaseExpresion;

class BitwiseExpresion implements BaseExpresion{
  private $arg;
  private $arg1;
  private $arg2;

  public function __construct(BaseExpresion $arg1, string $arg, BaseExpresion $arg2){
    $this->arg = $arg;
    $this->arg1 = $arg1;
    $this->arg2 = $arg2;
  }

  public function parse(\Ecma\Ecma $ecma){
    return \Math\Math::math($ecma->GetValue($this->arg1->parse($ecma)), $this->arg, $ecma->GetValue($this->arg2->parse($ecma)));
  }
}
