<?php
namespace Expresion\ObjectExpresion;

use Expresion\BaseExpresion\BaseExpresion;
use Types\Reference\Reference;

class ObjectExpresion implements BaseExpresion{
  private $base;
  private $name;

  public function __construct(BaseExpresion $base, BaseExpresion $name){
    $this->base = $base;
    $this->name = $name;
  }

  public function parse(\Ecma\Ecma $ecma){
    return new Reference(
      new \Types\Value\Value(
        "Object",
        $ecma->GetValue($this->base->parse($ecma))->ToObject()
        ),
      $this->name instanceof \Expresion\IdentifyExpresion\IdentifyExpresion ? $this->name->identify : $ecma->GetValue($this->name->parse($ecma))->ToString(),
      $ecma->getCurrentObject()
    );
  }
}
