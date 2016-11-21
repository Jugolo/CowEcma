<?php
namespace Ecma\Statment\VarStatment;

use Ecma\Types\Completion\Completion;
use Ecma\Statment\Statment\Statment;
use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Ecma\Ecma;

class VarStatment implements Statment{
  private $expresion;

  public function __construct(BaseExpresion $expresion){
    $this->expresion = $expresion;
  }
  public function parse(Ecma $ecma) : Completion{
    $this->expresion->parse($ecma);
    return new Completion(Completion::NORMAL);
  }
}
