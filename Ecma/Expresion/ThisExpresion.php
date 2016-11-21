<?php
namespace Ecma\Expresion\ThisExpresion;

use Ecma\Expresion\ExpresionResult\ExpresionResult;
use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Types\Value\Value;
use Ecma\Ecma\Ecma;

class ThisExpresion implements BaseExpresion{
  public function parse(Ecma $ecma) : ExpresionResult{
    return new ExpresionResult(new Value("Object", $ecma->getThis()));
  }
}
