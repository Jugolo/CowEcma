<?php
namespace Expresion\NullExpresion;

use Expresion\ExpresionResult\ExpresionResult;

class NullExpresion implements \Expresion\BaseExpresion\BaseExpresion{
  public function parse(\Ecma\Ecma $ecma) : ExpresionResult{
    return new ExpresionResult(new \Types\Value\Value("Null", null));
  }
}