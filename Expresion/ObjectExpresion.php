<?php
namespace Expresion\ObjectExpresion;

use Expresion\BaseExpresion\BaseExpresion;
use Expresion\ExpresionResult\ExpresionResult;
use Types\Reference\Reference;

class ObjectExpresion implements BaseExpresion{
  private $base;
  private $name;

  public function __construct(BaseExpresion $base, BaseExpresion $name){
    $this->base = $base;
    $this->name = $name;
  }

  public function parse(\Ecma\Ecma $ecma) : ExpresionResult{
    return new ExpresionResult(new Reference(
      $this->base->parse($ecma)->GetValue()->ToObject(),
      $this->name instanceof \Expresion\IdentifyExpresion\IdentifyExpresion ? $this->name->identify : $this->name->parse($ecma)->GetValue()->ToString()
    ));
  }
}