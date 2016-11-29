<?php
namespace Ecma\Expresion\UnaryExpresion;

use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Ecma\Ecma;
use Ecma\Types\Value\Value;
use Ecma\Types\Objects\HeadObject\HeadObjectDelete;
use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Refrence\Refrence;
use Ecma\Expresion\ExpresionResult\ExpresionResult;

class UnaryExpresion implements BaseExpresion{
  private $value;
  private $arg;

  public function __construct(BaseExpresion $value, string $arg){
    $this->arg = $arg;
    $this->value = $value;
  }

  public function parse(Ecma $ecma) : ExpresionResult{
    switch($this->arg){
      case "delete":
        return $this->doDelete($ecma);
      case "void":
        $this->value->parse($ecma);
        return new ExpresionResult(new Value($ecma, "Undefined", null));
      case "typeof":
        return $this->doTypeof($ecma);
      case "++":
        $value = ($operator = $this->value->Parse($ecma))->GetValue()->ToNumber()+1;
        $operator->GetBase()->PutValue(new Value($ecma, "Number", $value));
        return new ExpresionResult(new Value($ecma, "Number", $value));
      case "--":
        $value = ($operator = $this->value->Parse($ecma))->GetValue()->ToNumber()-1;
        $operator->GetBase()->PutValue(new Value($ecma, "Number", $value));
        return new ExpresionResult(new Value($ecma, "Number", $value));
      case "+":
        return new ExpresionResult(new Value($ecma, "Number", +$this->value->parse($ecma)->GetValue()->ToNumber()));
      case "-":
        return new ExpresionResult(new Value($ecma, "Number", -$this->value->parse($ecma)->GetValue()->ToNumber()));
      case "~":
        return new ExpresionResult(new Value($ecma, "Number", ~$this->value->parse($ecma)->GetValue()->ToNumber()));
      case "!":
        return new ExpresionResult(new Value($ecma, "Boolean", !$this->value->parse($ecma)->GetValue()->ToBoolean()));
    }
  }

  private function doTypeof(Ecma $ecma){
    $value = $this->value->parse($ecma);
    if($value->GetBase() instanceof Refrence && $value->GetBase() == null){
      return new ExpresionResult(new Value($ecma, "String", "undefined"));
    }

    return new ExpresionResult(new Value($ecma, "String", $value->GetValue()->type));
  }

  private function doDelete(Ecma $ecma){
    $value = $this->value->parse($ecma);
    $base  = $value->GetBase();

    if(!($base instanceof HeadObject))
      return new ExpresionResult(new Value($ecma, "Boolean", true));

    if($base instanceof HeadObjectDelete){
      return new ExpresionResult(new Value($ecma, "Boolean", $base->Delete($value->GetPropertyName())));
    }

    return new ExpresionResult(new Value($ecma, "Boolean", !$base->HasProperty($value->GetPropertyName())));
  }
}
