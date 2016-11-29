<?php
namespace Ecma\Types\Objects\String\StringPrototype\StringPrototype;

use Ecma\Ecma\Ecma;
use Ecma\Types\Value\Value;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Objects\String\StringConstructor\StringConstructor;
use Ecma\Types\Objects\String\StringInstance\StringInstance;

class StringPrototype extends HeadObject{
  public function __construct(StringConstructor $constructor, Ecma $ecma){
    $this->Put("constructor", new Property(new Value($ecma, "Object", $constructor)));
    $this->Put("toString", new Property(new Value($ecma, "Object", new StringToString())));
    $this->Put("valueOf", new Property(new Value($ecma, "Object", new StringValueOf())));
    $this->Put("charAt", new Property(new Value($ecma, "Object", new StringCharAt())));
  }
}

class StringCharAt extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $string = $obj->ToString();
    $pos = $arg[0]->ToNumber();
    $length = strlen($string);
    if($pos < 0 || $length < $pos){
      return new Value($obj->ecma, "String", "");
    }
    return new Value($obj->ecma, "String", $string[$pos]);
  }
}

class StringValueOf extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $o = $obj->ToObject();
    if(!($o instanceof StringInstance))
      throw new \RuntimeException("valueOf should be method of StringInstance!!");
    return new Value($obj->ecma, "String", $o->Value);
  }
}

class StringToString extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $o = $obj->ToObject();
    if(!($o instanceof StringInstance)){
      throw new \RuntimeException("toString should be method of StringInstance!!");
    }
    return new Value($obj->ecma, "String", $o->Value);
  }
}
