<?php
namespace Ecma\Expresion\ConditionalExpresion\ConditionalExpresion;

use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Expresion\ExpresionResult\ExpresionResult;
use Ecma\Ecma\Ecma;

class ConditionalExpresion implements BaseExpresion{
  private $conditional;
  private $arg1;
  private $arg2;

  public function __construct(BaseExpresion $con, BaseExpresion $arg1, BaseExpresion $arg2){
    $this->conditional = $con;
    $this->arg1 = $arg1;
    $this->arg2 = $arg2;
  }

  public function parse(Ecma $ecma) : ExpresionResult{
    $value = $this->conditional->parse($ecma)->GetValue()->ToBoolean();
    if($value){
      return $this->arg1->parse($ecma)->GetValue();
    }else{
      return $this->arg2->parse($ecma)->GetValue();
    }
  }
}
