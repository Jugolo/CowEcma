<?php
namespace Ecma\Expresion\ObjectExpresion;

use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Expresion\ExpresionResult\ExpresionResult;
use Ecma\Expresion\IdentifyExpresion\IdentifyExpresion;
use Ecma\Types\Reference\Reference;
use Ecma\Ecma\Ecma;

class ObjectExpresion implements BaseExpresion{
  private $base;
  private $name;

  public function __construct(BaseExpresion $base, BaseExpresion $name){
    $this->base = $base;
    $this->name = $name;
  }

  public function parse(Ecma $ecma) : ExpresionResult{
    return new ExpresionResult(new Reference(
      $this->base->parse($ecma)->GetValue()->ToObject(),
      $this->name instanceof IdentifyExpresion ? $this->name->identify : $this->name->parse($ecma)->GetValue()->ToString()
    ));
  }
}
