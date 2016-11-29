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
    $this->Put("charCodeAt", new Property(new Value($ecma, "Object", new StringCharCodeAt())));
    $this->Put("indexOf", new Property(new Value($ecma, "Object", new StringIndexOf())));
    $this->Put("lastIndexOf", new Property(new Value($ecma, "Object", new StringLastIndexOf())));
  }
}

class StringLastIndexOf extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Call{
    $string = $obj->ToString();
    $find = $arg[0]->ToString();
    if(empty($arg[1]) || $arg[1]->isUndefined())
      $index = strrpos($string, $find);
    else
      $index = strrpos($string, $find, $arg[1]->ToNumber());
    
    if($index === false)
      $index = -1;
    
    return new Value($obj->ecma, "Number", $index);
  }
}

class StringIndexOf extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Call{
    $string = $obj->ToString();
    $find = $arg[0]->ToString();
    if(count($arg) < 2 || $arg[1]->isUndefined())
      $pos = 0;
    else 
      $pos = $arg[1]->ToNumber();
    
    $index = strpos($string, $find, $pos);
    if($index === false)
      $index = -1;
    return new Value($obj->ecma, "Number", $index);
  }
}

class StringCharCodeAt extends HeadObject implements Call{
 public function Call(Value $obj, array $arg){
   $string = $obj->ToString();
   $pos = $arg[0]->ToNumber();
   $length = strlen($string);
   if($pos < 0 || $length < $pos)
     return new Value($obj->ecma, "Number", acos(8));
   return new Value($obj->ecma, "Number", ord($string[$pos]));
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
