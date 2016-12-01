<?php
namespace Ecma\Expresion\AssignExpresion;

use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Ecma\Ecma;
use Ecma\Types\Reference\Reference;
use Ecma\Types\Objects\Property\Property;
use Ecma\Expresion\ExpresionResult\ExpresionResult;
use Ecma\Expresion\IdentifyExpresion\IdentifyExpresion;
use Ecma\Math\Math;
use Ecma\Types\Value\Value;

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
         $second = $this->second->parse($ecma);
         if($this->arg == "+=" && ($first->GetValue()->isString() || $second->GetValue()->isString())){
           $first->GetBase()->PutValue(($result = new Value($ecma, "String", $first->GetValue()->ToString().$second->GetValue()->ToString())));
           return new ExpresionResult($result);
         }
        $result = Math::math(
            $first->GetValue(),
            substr($this->arg, 0, strlen($this->arg)-1),
            $second->GetValue()
        );
        $first->GetBase()->PutValue($result);
        return new ExpresionResult($result);
     }
  }

  private function lig(Ecma $ecma){
    if($this->first instanceof IdentifyExpresion){
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
