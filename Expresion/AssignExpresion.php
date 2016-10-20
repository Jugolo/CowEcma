<?php
namespace Expresion\AssignExpresion;

use Expresion\BaseExpresion\BaseExpresion;
use Ecma\Ecma;
use Types\Reference\Reference;
use Types\Objects\Property\Property;

class AssignExpresion implements BaseExpresion{
  private $first;
  private $arg;
  private $second;

  public function __construct(BaseExpresion $first, string $arg, BaseExpresion $second){
     $this->first  = $first;
     $this->arg    = $arg;
     $this->second = $second;
  }

  public function parse(Ecma $ecma){
     switch($this->arg){
       case "=":
        return $this->lig($ecma);
       default:
       $first = $this->first->parse($ecma);
        $result = \Math\Math::math(
            $ecma->GetValue($first),
            substr($this->arg, 0, strlen($this->arg)-1),
            $ecma->GetValue($this->second->parse($ecma))
        );
        $first->PutValue($result);
        return $result;
     }
  }

  private function lig(Ecma $ecma){
    if($this->first instanceof \Expresion\IdentifyExpresion\IdentifyExpresion){
      $ecma->getCurrentObject()->Put($this->first->identify, new Property(($value = $ecma->GetValue($this->second->parse($ecma)))));
      return $value;
    }
    $first = $this->first->parse($ecma);
    if($first instanceof Reference){
      $first->PutValue($value = $ecma->GetValue($this->second->parse($ecma)));
    }else{
      $ecma->getCurrentObject()->Put($this->first->identify, new Property($ecma->GetValue($value = $this->second->parse($ecma))));
    }
    return $value;
  }
}
