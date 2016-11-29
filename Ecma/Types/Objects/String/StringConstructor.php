<?php
namespace Ecma\Types\Objects\String\StringConstructor;

use Ecma\Ecma\Ecma;
use Ecma\Types\Value\Value;
use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Call\Call;

class StringConstructor extends HeadObject{
  protected $ecma;
  
  public function __construct(Ecma $ecma){
    $this->ecma = $ecma;
    $this->Prototype = $ecma->str = new StringPrototype($ecma);
  }
  
  public function Call(Value $obj, array $arg) : Value{
    if(count($arg) == 0)
      return new Value($this->ecma, "String", "");
    return new Value($this->ecma, "String", $arg[0]->ToString());
  }
}
