<?php
namespace Expresion\BoolExpresion;

use Expresion\ExpresionResult\ExpresionResult;

class BoolExpresion implements \Expresion\BaseExpresion\BaseExpresion{
  private $bool;

  public function __construct(string $b){
    $this->bool = $b == "true";
  }

  public function parse(\Ecma\Ecma $ecma) : ExpresionResult{
    return new ExpresionResult(new \Types\Value\Value("Boolean", $this->bool));
  }
}