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
    $this->Put("constructor",    new Property(new Value($ecma, "Object", $constructor)));
    $this->Put("toString",       new Property(new Value($ecma, "Object", new DateToString())));
    $this->Put("valueOf",        new Property(new Value($ecma, "Object", new DateValueOf())));
    $this->Put("getTime",        new Property(new Value($ecma, "Object", new DateGetTime())));
    $this->Put("getYear",        new Property(new Value($ecma, "Object", new DateGetYear())));
    $this->Put("getFullYear",    new Property(new Value($ecma, "Object", new DateGetFullYear())));
    $this->Put("getUTCFullYear", new Property(new Value($ecma, "Object", new DateGetUTCFullYear())));
    $this->Put("getMonth",       new Property(new Value($ecma, "Object", new DateGetMonth())));
  }
}

class DateGetMonth extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $value = $obj->ToObject()->Value;
    if(is_nan($value))
      return new Value($obj->ecma, "Number", $value);
    return new Value($obj->ecma, "Number", MonthFromTime(EcmaLocalTime($value)));
  }
}

class DateGetUTCFullYear extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $value = $obj->ToObject()->Value;
    if(is_nan($value))
      return new Value($obj->ecma, "Number", $value);
    
    return new Value($obj->ecma, "Number", YearFromTime($value));
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

//wee has looked in this file: https://github.com/mozilla/rhino/blob/master/src/org/mozilla/javascript/NativeDate.java to get it work

define("HalfTimeDomain", 8.64e15);
define("HoursPerDay", 24.0);
define("MinutesPerHour", 60.0);
define("SecondsPerMinute", 60.0);
define("msPerSecond", 1000.0);
define("MinutesPerDay", (HoursPerDay * MinutesPerHour));
define("SecondsPerDay", (MinutesPerDay * SecondsPerMinute));
define("SecondsPerHour", (MinutesPerHour * SecondsPerMinute));
define("msPerDay", (SecondsPerDay * msPerSecond));
define("msPerHour", (SecondsPerHour * msPerSecond));
define("msPerMinute", (SecondsPerMinute * msPerSecond));

function DayFromYear(int $y) : int{
    return ((365 * (($y)-1970) + floor((($y)-1969)/4.0) - floor((($y)-1901)/100.0) + floor((($y)-1601)/400.0)));
}
                     
function TimeFromYear(int $y) : int{
    return msPerDay*DayFromYear($y);
}

function DaysInYear(int $y) : int{
  return IsLeapYear((int)$y) ? 366.0 : 365.0;
}

function IsLeapYear(int $y) : bool{
  return $y % 4 == 0 && ($y % 100 != 0 || $y % 400 == 0);
}

function YearFromTime(int $t) : float{
    if(is_infinite($t) || is_nan($t)){
      return 0;
    }

    $y = floor($t / (msPerDay * 365.2425)) + 1970;
    $t2 = TimeFromYear($y);
    if($t < $t2)
      $y--;
    elseif ($t2 + msPerDay * DaysInYear($y) <= $t)
      $y++;

    return $y;
}
function EcmaLocalTime(int $t) : int{
    $localtza = new DateTime();
    return $t + $localtza->getOffset() + DaylightSavingTA($t);
}
function DaylightSavingTA($t){
    return 0;
}
function EcmaTime(){
  return time()*1000;
}
