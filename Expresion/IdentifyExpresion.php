<?php
namespace Expresion\IdentifyExpresion;

class IdentifyExpresion implements \Expresion\BaseExpresion\BaseExpresion{
  public $identify;

  public function __construct(string $identify){
    $this->identify = $identify;
  }

  public function parse(\Ecma\Ecma $ecma){
    return $ecma->getIdentify($this->identify);
  }
}
