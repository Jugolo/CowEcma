<?php
namespace Expresion\IdentifyExpresion;

use Expresion\ExpresionResult\ExpresionResult;

class IdentifyExpresion implements \Expresion\BaseExpresion\BaseExpresion{
  public $identify;

  public function __construct(string $identify){
    $this->identify = $identify;
  }

  public function parse(\Ecma\Ecma $ecma) : ExpresionResult{
    return new ExpresionResult($ecma->getIdentify($this->identify));
  }
}