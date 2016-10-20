<?php
namespace Expresion\ArrayExpresion;

use Expresion\BaseExpresion\BaseExpresion;

class ArrayExpresion implements BaseExpresion{
  private $arg1;
  private $arg2;

  public function __construct(BaseExpresion $arg1, BaseExpresion $arg2){
    $this->arg1 = $arg1;
    $this->arg2 = $arg2;
  }

  public function parse(\Ecma\Ecma $ecma){
    $base = $ecma->GetValue($this->arg1->parse($ecma))->ToObject();
    $name = $ecma->GetValue($this->arg2->parse($ecma))->ToString();
    return new \Types\Reference\Reference(new \Types\Value\Value("Object", $base), $name, $ecma->getCurrentObject());
  }
}
