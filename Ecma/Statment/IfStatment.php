<?php
namespace Ecma\Statment\IfStatment;

use Ecma\Statment\Statment\Statment;
use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Types\Completion\Completion;
use Ecma\Ecma\Ecma;

class IfStatment implements Statment{
  private $expresion;
  private $true;
  private $false;

  public function __construct(BaseExpresion $expresion, Statment $true, Statment $false){
    $this->expresion = $expresion;
    $this->true = $true;
    $this->false = $false;
  }

  public function parse(Ecma $ecma) : Completion{
    if($this->expresion->parse($ecma)->GetValue()->ToBoolean())
      return $this->true->parse($ecma);
    else
      return $this->false->parse($ecma);
  }
}
