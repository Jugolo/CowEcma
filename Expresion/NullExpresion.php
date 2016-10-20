<?php
namespace Expresion\NullExpresion;

class NullExpresion implements \Expresion\BaseExpresion\BaseExpresion{
  public function parse(\Ecma\Ecma $ecma){
    return new \Types\Value\Value("Null", null);
  }
}
