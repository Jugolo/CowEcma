<?php
namespace Ecma\Statment\WhileStatment;

use Ecma\Statment\Statment\Statment;
use Ecma\Types\Completion\Completion;
use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Ecma\Ecma;

class WhileStatment implements Statment{
  private $expresion;
  private $statment;

  public function __construct(BaseExpresion $expresion, Statment $statment){
    $this->expresion = $expresion;
    $this->statment  = $statment;
  }

  public function parse(Ecma $ecma) : Completion{
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

    return new Completion($ecma, Completion::NORMAL);
  }
}
