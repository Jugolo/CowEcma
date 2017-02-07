<?php
namespace Ecma\Types\Objects\String\StringConstructor;

use Ecma\Ecma\Ecma;
use Ecma\Types\Value\Value;
use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Objects\Constructor\Constructor;
use Ecma\Types\Objects\String\StringPrototype\StringPrototype;
use Ecma\Types\Objects\String\StringInstance\StringInstance;

class StringConstructor extends HeadObject implements Call, Constructor{
  protected $ecma;
  
  public function __construct(Ecma $ecma){
    $this->ecma = $ecma;
    $this->Prototype = $ecma->str = new StringPrototype($this, $ecma);
    $this->Put("prototype", new Property(new Value($ecma, "Object", $ecma->str), ["DontEnum", "DontDelete", "ReadOnly"]));
    $this->Put("fromCharCode", new Property(new Value($ecma, "Object", new StringFromCharCode())));
  }
  
  public function Construct(array $arg) : Value{
    if(count($arg) == 0)
      return new Value($this->ecma, "Object", new StringInstance("", $this->ecma));
    return new Value($this->ecma, "Object", new StringInstance($arg[0]->ToString(), $this->ecma));
  }
  
  public function Call(Value $obj, array $arg) : Value{
    if(count($arg) == 0)
      return new Value($this->ecma, "String", "");
    return new Value($this->ecma, "String", $arg[0]->ToString());
  }
}

class StringFromCharCode extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $buffer = "";
    for($i=0;$i<count($arg);$i++)
      $buffer .= chr($arg[$i]->ToNumber());
    return new Value($obj->ecma, "String", $buffer);
  }
}
