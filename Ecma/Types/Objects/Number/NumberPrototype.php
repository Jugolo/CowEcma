<?php
namespace Ecma\Types\Objects\Number\NumberPrototype;

use Ecma\Types\Objects\Number\NumberConstructor\NumberConstructor;
use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Value\Value;
use Ecma\Ecma\Ecma;

class NumberPrototype extends HeadObject{
  protected $ecma;
  
  public function __construct(Ecma $ecma, NumberConstructor $constructor){
    $this->ecma = $ecma;
    $this->Class = "Number";
    $this->Prototype = $ecma->object->Get("prototype")->GetValue();
    
    $this->Put("constructor", new Property(new Value($ecma, "Object", $constructor)));
    $this->Put("toString", new Property(new Value($ecma, "Object", new NumberToString())));
  }
}

class NumberToString extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    if(count($arg) == 0 || $arg->ToNumber() == 10)
      return new Value($obj->ecma, "String", $obj->ToString());
    
    
  }
}
