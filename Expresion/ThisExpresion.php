<?php
namespace Expresion\ThisExpresion;

use Expresion\ExpresionResult\ExpresionResult;

class ThisExpresion implements \Expresion\BaseExpresion\BaseExpresion{
  public function parse(\Ecma\Ecma $ecma) : ExpresionResult{
    return new ExpresionResult(new \Types\Value\Value("Object", $ecma->getThis()));
  }
}