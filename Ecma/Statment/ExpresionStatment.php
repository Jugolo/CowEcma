<?php
namespace Ecma\Statment\ExpresionStatment;

use Ecma\Types\Completion\Completion;
use Ecma\Statment\Statment\Statment;
use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Ecma\Ecma;

class ExpresionStatment implements Statment{
  private $expresion;

  public function __construct(BaseExpresion $expresion){
    $this->expresion = $expresion;
  }

  public function parse(Ecma $ecma) : Completion{
     return new Completion($ecma, Completion::NORMAL, $this->expresion->parse($ecma));
  }
}
