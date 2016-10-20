<?php
namespace Expresion\BoolExpresion;

class BoolExpresion implements \Expresion\BaseExpresion\BaseExpresion{
  private $bool;

  public function __construct(string $b){
    $this->bool = $b == "true";
  }

  public function parse(\Ecma\Ecma $ecma){
    return new \Types\Value\Value("Boolean", $this->bool);
  }
}
