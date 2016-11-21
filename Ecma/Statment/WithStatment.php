<?php
namespace Ecma\Statment\WithStatment;

use Ecma\Statment\Statment\Statment;
use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Types\Completion\Completion;
use Ecma\ExcuteContext\ExcuteContext;
use Ecma\Ecma\Ecma;

class WithStatment implements Statment{
  private $expresion;
  private $body;

  public function __construct(BaseExpresion $expresion, Statment $body){
    $this->expresion = $expresion;
    $this->body = $body;
  }

  public function parse(Ecma $ecma) : Completion{
    $ecma->enterScope(
      new ExcuteContext(
        ($e = $this->expresion->parse($ecma)->GetValue()->ToObject()),
        $e,
        $e
      )
    );

    $com = $this->body->parse($ecma);
    $ecma->leaveScope();
    return $com;
  }
}
