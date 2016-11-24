<?php
namespace Ecma\Expresion\EmptyExpresion;

use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Expresion\ExpresionResult\ExpresionResult;
use Ecma\Types\Value\Value;
use Ecma\Ecma\Ecma;

class EmptyExpresion implements BaseExpresion{
  public function parse(Ecma $ecma) : ExpresionResult{
    return new ExpresionResult(new Value("Undefined", null));
  }
}
