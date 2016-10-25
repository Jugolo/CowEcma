<?php
namespace Expresion\FunctionExpresion;

use Expresion\ExpresionResult\ExpresionResult;

class FunctionExpresion implements \Expresion\BaseExpresion\BaseExpresion{
  private $name;
  private $args;
  private $body;

  public function __construct(string $name, array $args, string $body){
    $this->name = $name;
    $this->args = array_map(function($v){
      return new \Types\Value\Value("String", $v);
    }, $args);
    $this->body = new \Types\Value\Value("String", $body);
  }

  public function parse(\Ecma\Ecma $ecma) : ExpresionResult{
    $func = $ecma->getIdentify("Function");
    $obj = $func->GetValue()->value->Construct(array_merge($this->args, [$this->body]));
    $ecma->pushVariabel($this->name, $obj);
    return new ExpresionResult($obj);
  }
}