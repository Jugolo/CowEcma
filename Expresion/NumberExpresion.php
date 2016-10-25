<?php
namespace Expresion\NumberExpresion;

use Expresion\ExpresionResult\ExpresionResult;

class NumberExpresion implements \Expresion\BaseExpresion\BaseExpresion{
  private $value;

  public function __construct(string $number){
    $this->value = intval($number);
  }

  public function parse(\Ecma\Ecma $ecma) : ExpresionResult{
    return new ExpresionResult(new \Types\Value\Value("Number", (int)$this->value));
  }
}