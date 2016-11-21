<?php
namespace Ecma\Expresion\Expresion;

use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Expresion\ExpresionResult\ExpresionResult;
use Ecma\Ecma\Ecma;

class Expresion implements BaseExpresion{
  private $expresion;

  public function __construct(array $expresion){
   $this->expresion = $expresion;
  }

  public function parse(Ecma $ecma) : ExpresionResult{
    for($i=0;$i<count($this->expresion);$i++)
      $result = $this->expresion[$i]->parse($ecma);
    return $result;
  }
}
