<?php
namespace Statment\IfStatment;

use Statment\Statment\Statment;

class IfStatment implements Statment{
  private $expresion;
  private $true;
  private $false;

  public function __construct(\Expresion\BaseExpresion\BaseExpresion $expresion, Statment $true, Statment $false){
    $this->expresion = $expresion;
    $this->true = $true;
    $this->false = $false;
  }

  public function parse(\Ecma\Ecma $ecma) : \Types\Completion\Completion{
    if($this->expresion->parse($ecma)->GetValue()->ToBoolean())
      return $this->true->parse($ecma);
    else
      return $this->false->parse($ecma);
  }
}