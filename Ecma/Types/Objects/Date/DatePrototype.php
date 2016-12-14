<?php
namespace Ecma\Types\Objects\Date\DataPrototype;

use Ecma\Types\Objects\HeadObject;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Objects\Date\DateConstructor\DateConstructor;
use Ecma\Types\Objects\Date\DateInstance\DateInstance;
use Ecma\Types\Value\Value;
use Ecma\Ecma\Ecma;

class DatePrototype extends HeadObject{
  protected $ecma;
  
  public function __construct(Ecma $ecma, DateConstructor $constructor){
    $this->ecma = $ecma;
    $this->Put("constructor", new Property(new Value($ecma, "Object", $constructor)));
    $this->Put("toString",    new Property(new Value($ecma, "Object", new DateToString())));
    $this->Put("valueOf",     new Property(new Value($ecma, "Object", new DateValueOf())));
    $this->Put("getTime",     new Property(new Value($ecma, "Object", new DateGetTime())));
    $this->Put("getYear",     new Property(new Value($ecma, "Object", new DateGetYear())));
    $this->Put("getFullYear", new Property(new Value($ecma, "Object", new DateGetFullYear())));
  }
}

class DateGetFullYear extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $value = $obj->ToObject()->Value;
    if(is_nan($value))
      return new Value($obj->ecma, "Number", $value);
    
    return new Value($obj->ecma, "Number", YearFromTime(EcmaLocalTime($value)));
  }
}

class DateGetYear extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $value = $obj->ToObject()->Value;
    if(is_nan($value))
      return new Value($obj->ecma, "Number", $value);
    
    return new Value($obj->ecma, "Number", YearFromTime(EcmaLocalTime($value)) - 1900);
  }
}

class DateGetTime extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $o = $obj->ToObject();
    
    if($o->Class != "Date")
      throw new RuntimeException("getTime must be method of Date class");
    
    return new Value($obj->ecma, "Number", $o->Value);
  }
}

class DateValueOf extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $o = $obj->ToObject();
    if(!($o instanceof DateInstance))
      throw new RuntimeException("Date.valueOf method should be method of Date instance");
    
    return new Value($obj->ecma, "Number", $o->Value);
  }
}

class DateToString extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $o = $obj->ToObject();
    if(!($o instanceof DateInstance)){
      throw new \RuntimeException("Date.toString method should be method of Date instance");
    }
    
    $str  = $o->Get("getDate")->GetValue()->Call($obj, [])->ToString()."/";
    $str .= $o->Get("getMonth")->GetValue()->Call($obj, [])->ToString()."/";
    $str .= $o->Get("getYear")->GetValue()->Call($obj, [])->ToString()." ";
    $str .= $o->Get("getHours")->GetValue()->Call($obj, [])->ToString().":";
    $str .= $o->Get("getMinutes")->GetValue()->Call($obj, [])->ToString().":";
    $str .= $o->Get("getSeconds")->GetValue()->Call($obj, [])->ToString();
    return new Value($obj->ecma, "String", $str);
  }
}
                     
function DayFromYear(int $y) : int{
    return 365 * ($y-1970) + floor(($y-1969)/4) - floor(($y-1901)/100) + floor(($y-1601)/400);
}
                     
function TimeFromYear(int $y){
    return 86400000*DayFromYear($y);
}
                     
function YearFromTime(int $y) : int{
    return TimeFromYear($y);
}

function EcmaLocalTime(int $t) : int{
    $localtza = 0;
    return $t + $localtza + DaylightSavingTA($t);
}
