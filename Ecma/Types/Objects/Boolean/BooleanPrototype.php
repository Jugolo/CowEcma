<?php
namespace Ecma\Types\Objects\Boolean\BooleanPrototype;

use Ecma\Types\Objects\Boolean\BooleanConstructor\BooleanConstructor;
use Ecma\Types\Objects\Boolean\BooleanInstance\BooleanInstance;
use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Object\Property\Property;
use Ecma\Ecma\Ecma;
use Ecma\Types\Value\Value;

class BooleanPrototype extends HeadObject{
  protected $ecma;
  
  public function __construct(Ecma $ecma, BooleanConstructor $constructor){
    $this->ecma = $ecma;
    $this->Class = "Boolean";
    $this->Put("constructor", new Property(new Value($ecma, "Object", $constructor)));
    $this->Put("toString", new Property(new Value($ecma, "Object", new BooleanToString())));
    $this->Put("valueOf", new Property(new Value($ecma, "Object", new BooleanValueOf())));
  }
}

class BooleanValueOf extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $o = $obj->ToObject();
    if(!($o instanceof BooleanInstance)){
      throw new \RuntimeException("Boolean.valueOf() object must be Boolean!");
    }
    
    return $obj;
  }
}

class BooleanToString extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $o = $obj->ToObject();
    if(!($o instanceof BooleanInstance)){
      throw new \RuntimeException("Boolean.toString() object must be Boolean!");
    }
    
    return new Value($obj->ecma, "String", $o->Value ? "true" : "false");
  }
}
