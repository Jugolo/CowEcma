<?php
namespace Statment\FunctionStatment;

use Types\Completion\Completion;

class FunctionStatment implements \Statment\Statment\Statment{
  private $expresion;

  public function __construct(\Expresion\BaseExpresion\BaseExpresion $expresion){
     $this->expresion = $expresion;
  }

  public function parse(\Ecma\Ecma $ecma) : Completion{
    $this->expresion->parse($ecma);
    return new Completion(Completion::NORMAL);
  }
}