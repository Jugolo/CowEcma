<?php
namespace Statment\WithStatment;

use Statment\Statment\Statment;

class WithStatment implements Statment{
  private $expresion;
  private $body;

  public function __construct(\Expresion\BaseExpresion\BaseExpresion $expresion, Statment $body){
    $this->expresion = $expresion;
    $this->body = $body;
  }

  public function parse(\Ecma\Ecma $ecma) : \Types\Completion\Completion{
    $ecma->enterScope(
      new \ExcuteContext\ExcuteContext(
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
