<?php
namespace Expresion\UnaryExpresion;

use Expresion\BaseExpresion\BaseExpresion;
use Ecma\Ecma;
use Types\Value\Value;
use Types\Objects\HeadObject\HeadObjectDelete;
use Types\Refrence\Refrence;

class UnaryExpresion implements BaseExpresion{
  private $value;
  private $arg;

  public function __construct(BaseExpresion $value, string $arg){
    $this->arg = $arg;
    $this->value = $value;
  }

  public function parse(Ecma $ecma){
    switch($this->arg){
      case "delete":
        return $this->doDelete($ecma);
      case "void":
        $ecma->GetValue($this->value->parse($ecma));
        return new Value("Undefined", null);
      case "typeof":
        return $this->doTypeof($ecma);
      case "++":
        $value = ($operator = $this->value->Parse($ecma))->GetValue()->ToNumber()+1;
        $operator->PutValue(new Value("Number", $value));
        return new Value("Number", $value);
      case "--":
        $value = ($operator = $this->value->Parse($ecma))->GetValue()->ToNumber()-1;
        $operator->PutValue(new Value("Number", $value));
        return new Value("Number", $value);
      case "+":
        return new Value("Number", +$this->value->parse($ecma)->GetValue()->ToNumber());
      case "-":
        return new Value("Number", -$this->value->parse($ecma)->GetValue()->ToNumber());
      case "~":
        return new Value("Number", ~$this->value->parse($ecma)->GetValue()->ToNumber());
      case "!":
        return new Value("Boolean", !$this->value->parse($ecma)->GetValue()->ToBoolean());
    }
  }

  private function doTypeof(Ecma $ecma){
    $value = $this->value->parse($ecma);
    if($value instanceof Refrence && $value->GetBase() == null){
      return new Value("String", "undefined");
    }

    return new Value("String", $ecma->GetValue($value)->type);
  }

  private function doDelete(Ecma $ecma){
    $value = $this->value->parse($ecma);
    $base  = $value->GetBase();

    if(!($base instanceof \Types\Objects\HeadObject\HeadObject))
      return new Value("Boolean", true);

    if($base instanceof HeadObjectDelete){
      return new Value("Boolean", $base->Delete($value->GetPropertyName()));
    }

    return new Value("Boolean", !$base->HasProperty($value->GetPropertyName()));
  }
}
