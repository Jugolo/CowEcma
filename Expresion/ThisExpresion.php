<?php
namespace Expresion\ThisExpresion;

class ThisExpresion implements \Expresion\BaseExpresion\BaseExpresion{
  public function parse(\Ecma\Ecma $ecma){
    return new \Types\Value\Value("Object", $ecma->getCurrentObject());
  }
}
