<?php
namespace Ecma\Expresion\IdentifyExpresion;

use Ecma\Expresion\ExpresionResult\ExpresionResult;
use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Ecma\Ecma;

class IdentifyExpresion implements BaseExpresion{
  public $identify;

  public function __construct(string $identify){
    $this->identify = $identify;
  }

  public function parse(Ecma $ecma) : ExpresionResult{
    return new ExpresionResult($ecma->getIdentify($this->identify));
  }
}
