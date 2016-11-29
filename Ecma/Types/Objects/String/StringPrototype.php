<?php
namespace Ecma\Types\Objects\String\StringPrototype\StringPrototype;

use Ecma\Ecma\Ecma;
use Ecma\Types\Value\Value;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Objects\String\StringConstructor\StringConstructor;
use Ecma\Types\Objects\String\StringInstance\StringInstance;
use Ecma\Types\Objects\Arrays\ArrayInstance\ArrayInstance;

class StringPrototype extends HeadObject{
  public function __construct(StringConstructor $constructor, Ecma $ecma){
    $this->Put("constructor", new Property(new Value($ecma, "Object", $constructor)));
    $this->Put("toString", new Property(new Value($ecma, "Object", new StringToString())));
    $this->Put("valueOf", new Property(new Value($ecma, "Object", new StringValueOf())));
    $this->Put("charAt", new Property(new Value($ecma, "Object", new StringCharAt())));
    $this->Put("charCodeAt", new Property(new Value($ecma, "Object", new StringCharCodeAt())));
    $this->Put("indexOf", new Property(new Value($ecma, "Object", new StringIndexOf())));
    $this->Put("lastIndexOf", new Property(new Value($ecma, "Object", new StringLastIndexOf())));
    $this->Put("split", new Property(new Value($ecma, "Object", new StringSplit())));
    $this->Put("substr", new Property(new Value($ecma, "Object", new StringSubStr())));
    $this->Put("toLowerCase", new Property(new Value($ecma, "Object", new StringToLowerCase())));
    $this->Put("toUpperCase", new Property(new Value($ecma, "Object", new StringToUpperCase())));
  }
}

class StringToUpperCase extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    return new Value($obj->ecma, "String", strtoupper($obj->ToString()));
  }
}

class StringToLowerCase extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    return new Value($obj->ecma, "String", strtolower($obj->ToString()));
  }
}

class StringSubStr extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $string = $obj->ToObject();
    if(count($arg) < 2){
      $min = min(max($arg[0]->ToNumber(), 0), strlen($string));
      $max = strlen($string);
    }else{
      $p5 = min(max($arg[0]->ToNumber(), 0), strlen($string));
      $p6 = min(max($arg[1]->ToNumber(), 0), strlen($string));
      $min = min($p5, $p6);
      $max = max($p5, $p6);
    }
    $buffer = "";
    for($i=$min;$i<$max;$i++){
      $buffer .= $string[$i];
    }
    return new Value($obj->ecma, "String", $buffer);
  }
}

class StringSplit extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $string = $obj->ToString();
    $split = $arg[0]->ToString();
    $ar = explode($arg[0]->ToString(), $obj->ToString(), empty($arg[1]) || $arg[1]->isUndefined() ? PHP_INT_MAX : $arg[1]->ToNumber());
    $array = new ArrayInstance();
    for($i=0;$i<count($ar);$i++){
      $array->Put(strval($i), new Property(new Value($obj->ecma, "String", $ar[$i])));
    }
    return new Value($obj->ecma, "Object", $array);
  }
}

class StringLastIndexOf extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
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
  public function Call(Value $obj, array $arg) : Value{
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
 public function Call(Value $obj, array $arg) : Value{
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
