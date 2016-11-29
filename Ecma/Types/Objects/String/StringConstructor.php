<?php
namespace Ecma\Types\Objects\String\StringConstructor;

use Ecma\Ecma\Ecma;
use Ecma\Types\Value\Value;
use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Objects\Property\Property;

class StringConstructor extends HeadObject implements Call{
  protected $ecma;
  
  public function __construct(Ecma $ecma){
    $this->ecma = $ecma;
    $this->Prototype = $ecma->str = new StringPrototype($ecma);
    
    $this->Put("fromCharCode", new Property(new Value($ecma, "Object", new StringFromCharCode())));
  }
  
  public function Call(Value $obj, array $arg) : Value{
    if(count($arg) == 0)
      return new Value($this->ecma, "String", "");
    return new Value($this->ecma, "String", $arg[0]->ToString());
  }
}

class StringFromCharCode extends HeadObject implements Call{
  public function Call(Value $obj, array $arg){
    $buffer = "";
    for($i=0;$i<count($arg);$i++)
      $buffer .= chr($arg[$i]->ToNumber());
    return new Value($obj->ecma, "String", $buffer);
  }
}
