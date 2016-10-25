<?php
namespace Statment\ExpresionStatment;

use Types\Completion\Completion;

class ExpresionStatment implements \Statment\Statment\Statment{
  private $expresion;

  public function __construct(\Expresion\BaseExpresion\BaseExpresion $expresion){
    $this->expresion = $expresion;
  }

  public function parse(\Ecma\Ecma $ecma) : Completion{
     return new Completion(Completion::NORMAL, $this->expresion->parse($ecma));
  }
}