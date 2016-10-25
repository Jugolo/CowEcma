<?php
namespace Expresion\Expresion;

use Expresion\BaseExpresion\BaseExpresion;
use Expresion\ExpresionResult\ExpresionResult;

class Expresion implements BaseExpresion{
  private $expresion;

  public function __construct(array $expresion){
   $this->expresion = $expresion;
  }

  public function parse(\Ecma\Ecma $ecma) : ExpresionResult{
    for($i=0;$i<count($this->expresion);$i++)
      $result = $this->expresion[$i]->parse($ecma);
    return $result;
  }
}