<?php
namespace Ecma\Expresion\NullExpresion;

use Ecma\Expresion\ExpresionResult\ExpresionResult;
use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Types\Value\Value;
use Ecma\Ecma\Ecma;

class NullExpresion implements BaseExpresion{
  public function parse(Ecma $ecma) : ExpresionResult{
    return new ExpresionResult(new Value($ecma, "Null", null));
  }
}
