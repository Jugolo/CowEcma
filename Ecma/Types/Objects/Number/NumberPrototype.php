<?php
namespace Ecma\Types\Objects\Number\NumberPrototype;

use Ecma\Types\Objects\Number\NumberConstructor\NumberConstructor;
use Ecma\Types\Objects\Number\NumberInstance\NumberInstance;
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
    $this->Put("valueOf", new Property(new Value($ecma, "Object", new NumberValueOf())));
  }
}

class NumberValueOf extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    if(!($obj->ToObject() instanceof NumberInstance))
      throw new \RuntimeException("Number.valueOf method must be in a number instance");
    return new Value($obj->value, "Number", $obj->ToNumber());
  }
}

class NumberToString extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    if(!($obj->ToObject() instanceof NumberInstance))
      throw new \RuntimeException("Number.toString must be in a number object");
    if(count($arg) == 0 || $arg[0]->ToNumber() == 10)
      return new Value($obj->ecma, "String", $obj->ToString());
    
    return new Value($obj->ecma, "String", base_convert($arg[0]->ToNumber()));
  }
}
