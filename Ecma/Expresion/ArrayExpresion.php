<?php
namespace Ecma\Expresion\ArrayExpresion;

use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Expresion\ExpresionResult\ExpresionResult;
use Ecma\Types\Reference\Reference;
use Ecma\Ecma\Ecma;

class ArrayExpresion implements BaseExpresion{
  private $arg1;
  private $arg2;

  public function __construct(BaseExpresion $arg1, BaseExpresion $arg2){
    $this->arg1 = $arg1;
    $this->arg2 = $arg2;
  }

  public function parse(Ecma $ecma) : ExpresionResult{
    return new ExpresionResult(new Reference(
       $this->arg1->parse($ecma)->GetValue()->ToObject(),
       $this->arg2->parse($ecma)->GetValue()->ToString()
    ));
  }
}
