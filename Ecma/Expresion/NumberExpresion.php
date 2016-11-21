<?php
namespace Ecma\Expresion\NumberExpresion;

use Ecma\Expresion\ExpresionResult\ExpresionResult;
use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Types\Value\Value;
use Ecma\Ecma\Ecma;

class NumberExpresion implements BaseExpresion{
  private $value;

  public function __construct(string $number){
    $this->value = intval($number);
  }

  public function parse(Ecma $ecma) : ExpresionResult{
    return new ExpresionResult(new Value("Number", (int)$this->value));
  }
}
