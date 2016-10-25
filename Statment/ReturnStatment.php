<?php
namespace Statment\ReturnStatment;

use Types\Completion\Completion;

class ReturnStatment implements \Statment\Statment\Statment{
  private $expresion;

  public function __construct(\Expresion\BaseExpresion\BaseExpresion $expresion){
    $this->expresion = $expresion;
  }

  public function parse(\Ecma\Ecma $ecma) : Completion{
    return new Completion(Completion::RETURNS, $this->expresion->parse($ecma));
  }
}