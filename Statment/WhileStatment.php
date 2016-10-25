<?php
namespace Statment\WhileStatment;

use Statment\Statment\Statment;
use Types\Completion\Completion;

class WhileStatment implements Statment{
  private $expresion;
  private $statment;

  public function __construct(\Expresion\BaseExpresion\BaseExpresion $expresion, Statment $statment){
    $this->expresion = $expresion;
    $this->statment  = $statment;
  }

  public function parse(\Ecma\Ecma $ecma) : Completion{
    while($this->expresion->parse($ecma)->GetValue()->ToBoolean()){
      $completion = $this->statment->parse($ecma);
      if(!$completion->isNormal()){
        if($completion->isReturn()){
          return $completion;
        }elseif($completion->isBreak()){
          break;
        }elseif($completion->isContinue()){
          //do nothing
        }
      }
    }

    return new Completion(Completion::NORMAL);
  }
}
