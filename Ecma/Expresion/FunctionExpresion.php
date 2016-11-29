<?php
namespace Ecma\Expresion\FunctionExpresion;

use Ecma\Expresion\ExpresionResult\ExpresionResult;
use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Types\Value\Value;
use Ecma\Ecma\Ecma;

class FunctionExpresion implements BaseExpresion{
  private $name;
  private $args;
  private $body;

  public function __construct(string $name, array $args, string $body, Ecma $ecma){
    $this->name = $name;
    $this->args = array_map(function($v) use($ecma){
      return new Value($ecma, "String", $v);
    }, $args);
    $this->body = new Value($ecma, "String", $body);
  }

  public function parse(Ecma $ecma) : ExpresionResult{
    $func = $ecma->getIdentify("Function");
    $obj = $func->GetValue()->value->Construct(array_merge($this->args, [$this->body]));
    $ecma->pushVariabel($this->name, $obj);
    return new ExpresionResult($obj);
  }
}
