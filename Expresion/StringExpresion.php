<?php
namespace Expresion\StringExpresion;

class StringExpresion implements \Expresion\BaseExpresion\BaseExpresion{
  private $str;

  public function __construct(string $str){
    $this->str = $str;
  }

  public function parse(\Ecma\Ecma $ecma){
    return new \Types\Value\Value("String", $this->str);
  }
}
