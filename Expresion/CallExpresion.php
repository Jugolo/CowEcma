<?php
namespace Expresion\CallExpresion;

use Expresion\BaseExpresion\BaseExpresion;

class CallExpresion implements BaseExpresion{
  private $func;
  private $args;

  public function __construct(BaseExpresion $expresion, array $arguments){
    $this->func = $expresion;
    $this->args = $arguments;
  }

  public function parse(\Ecma\Ecma $ecma){
    $func = $ecma->GetValue($this->func->parse($ecma));
    if(!$func->isObject())
      throw new \RuntimeException("Try to use a non object to function call");

    if(!($func->value instanceof \Types\Objects\Call\Call)){
      throw new \RuntimeException("Object don't contain Call");
    }

    $args = [];
    for($i=0;$i<count($this->args);$i++){
      $args[] = $ecma->GetValue($this->args[$i]->parse($ecma));
    }

    return $func->value->Call(
      $func instanceof \Types\Reference\Reference ? $func->GetBase() : null,
      $args
    );
  }
}
