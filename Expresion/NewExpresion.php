<?php
namespace Expresion\NewExpresion;

use Expresion\BaseExpresion\BaseExpresion;

class NewExpresion implements BaseExpresion{
  private $left;
  private $right;

  public function __construct(BaseExpresion $left, array $right){
    $this->left = $left;
    $this->right = $right;
  }

  public function parse(\Ecma\Ecma $ecma){
    $left = $ecma->GetValue($this->left->parse($ecma));
    if(!$left->IsObject()){
      throw new RuntimeException("Missing object after 'new'");
    }

    if(!($left->value instanceof \Types\Objects\Constructor\Constructor)){
      throw new RuntimeException("The object after 'new' does containe a constructor!");
    }

    $args = [];
    for($i=0;$i<count($this->right);$i++){
      $args[] = $ecma->GetValue($this->right->parse($ecma));
    }

    $obj = $left->value->Construct($args);
    if(!$obj->isObject()){
      throw new RuntimeException("The object Construct dont return a object");
    }
    
    return $obj;
  }
}
