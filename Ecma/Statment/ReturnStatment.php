<?php
namespace Ecma\Statment\ReturnStatment;

use Ecma\Types\Completion\Completion;
use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Statment\Statment\Statment;
use Ecma\Ecma\Ecma;

class ReturnStatment implements Statment{
  private $expresion;

  public function __construct(BaseExpresion $expresion){
    $this->expresion = $expresion;
  }

  public function parse(Ecma $ecma) : Completion{
    return new Completion(Completion::RETURNS, $this->expresion->parse($ecma));
  }
}
