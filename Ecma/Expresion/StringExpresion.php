<?php
namespace Ecma\Expresion\StringExpresion;

use Ecma\Expresion\ExpresionResult\ExpresionResult;
use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Types\Value\Value;
use Ecma\Ecma\Ecma;

class StringExpresion implements BaseExpresion{
  private $str;

  public function __construct(string $str){
    $this->str = $str;
  }

  public function parse(Ecma $ecma) : ExpresionResult{
    return new ExpresionResult(new Value("String", $this->str));
  }
}
