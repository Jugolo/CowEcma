<?php
namespace Ecma\Expresion\BoolExpresion;

use Ecma\Expresion\ExpresionResult\ExpresionResult;
use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Types\Value\Value;
use Ecma\Ecma\Ecma;

class BoolExpresion implements BaseExpresion{
  private $bool;

  public function __construct(string $b){
    $this->bool = $b == "true";
  }

  public function parse(Ecma $ecma) : ExpresionResult{
    return new ExpresionResult(new Value($ecma, "Boolean", $this->bool));
  }
}
