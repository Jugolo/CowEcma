<?php
namespace Expresion\AssignExpresion;

use Expresion\BaseExpresion\BaseExpresion;
use Ecma\Ecma;
use Types\Reference\Reference;
use Types\Objects\Property\Property;
use Expresion\ExpresionResult\ExpresionResult;

class AssignExpresion implements BaseExpresion{
  private $first;
  private $arg;
  private $second;

  public function __construct(BaseExpresion $first, string $arg, BaseExpresion $second){
     $this->first  = $first;
     $this->arg    = $arg;
     $this->second = $second;
  }

  public function parse(Ecma $ecma) : ExpresionResult{
     switch($this->arg){
       case "=":
        return $this->lig($ecma);
       default:
       $first = $this->first->parse($ecma);
        $result = \Math\Math::math(
            $first->GetValue(),
            substr($this->arg, 0, strlen($this->arg)-1),
            $this->second->parse($ecma)->GetValue()
        );
        $first->GetBase()->PutValue($result);
        return new ExpresionResult($result);
     }
  }

  private function lig(Ecma $ecma){
    if($this->first instanceof \Expresion\IdentifyExpresion\IdentifyExpresion){
      $ecma->pushVariabel($this->first->identify, ($value = $this->second->parse($ecma)->GetValue()));
      return new ExpresionResult($value);
    }
    $first = $this->first->parse($ecma)->GetBase();
    if($first instanceof Reference){
      $first->PutValue($value = $this->second->parse($ecma)->GetValue());
    }else{
      $ecma->pushVariabel($this->first->identify, $value = $this->second->parse($ecma)->GetValue());
    }
    return new ExpresionResult($value);
  }
}