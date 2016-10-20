<?php
namespace Expresion\NumberExpresion;

class NumberExpresion implements \Expresion\BaseExpresion\BaseExpresion{
  private $value;

  public function __construct(string $number){
    $this->value = intval($number);
  }

  public function parse(\Ecma\Ecma $ecma){
    return new \Types\Value\Value("Number", $this->value);
  }
}
