<?php
namespace Ecma\Expresion\NewExpresion;

use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Expresion\ExpresionResult\ExpresionResult;
use Ecma\Types\Objects\Constructor\Constructor;
use Ecma\Ecma\Ecma;

class NewExpresion implements BaseExpresion{
  private $left;
  private $right;

  public function __construct(BaseExpresion $left, array $right){
    $this->left = $left;
    $this->right = $right;
  }

  public function parse(Ecma $ecma) : ExpresionResult{
    $left = $this->left->parse($ecma)->GetValue();
    if(!$left->IsObject()){
      throw new \RuntimeException("Missing object after 'new'");
    }

    if(!($left->value instanceof Constructor)){
      throw new \RuntimeException("The object after 'new' does containe a constructor!");
    }

    $args = [];
    for($i=0;$i<count($this->right);$i++){
      $args[] = $this->right[$i]->parse($ecma)->GetValue();
    }

    $obj = $left->value->Construct($args);
    if(!$obj->isObject()){
      throw new \RuntimeException("The object Construct dont return a object");
    }

    return new ExpresionResult($obj);
  }
}
