<?php
namespace Expresion\ArrayExpresion;

use Expresion\BaseExpresion\BaseExpresion;
use Expresion\ExpresionResult\ExpresionResult;

class ArrayExpresion implements BaseExpresion{
  private $arg1;
  private $arg2;

  public function __construct(BaseExpresion $arg1, BaseExpresion $arg2){
    $this->arg1 = $arg1;
    $this->arg2 = $arg2;
  }

  public function parse(\Ecma\Ecma $ecma) : ExpresionResult{
    return new ExpresionResult(new \Types\Reference\Reference(
       $this->arg1->parse($ecma)->GetValue()->ToObject(),
       $this->arg2->parse($ecma)->GetValue()->ToString()
    ));
  }
}