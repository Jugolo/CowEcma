<?php
namespace Expresion\StringExpresion;

use Expresion\ExpresionResult\ExpresionResult;

class StringExpresion implements \Expresion\BaseExpresion\BaseExpresion{
  private $str;

  public function __construct(string $str){
    $this->str = $str;
  }

  public function parse(\Ecma\Ecma $ecma) : ExpresionResult{
    return new ExpresionResult(new \Types\Value\Value("String", $this->str));
  }
}