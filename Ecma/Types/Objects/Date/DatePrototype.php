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
    $this->Put("getUTCMonth",    new Property(new Value($ecma, "Object", new DateGetUTCMonth())));
    $this->Put("getDate",        new Property(new Value($ecma, "Object", new DateGetDate())));
    $this->Put("getUTCDate",     new Property(new Value($ecma, "Object", new DateGetUTCDate())));
  }
}

class DateGetUTCDate extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = $obj->ToObject()->Value;
    if(is_nan($t))
      return new Value($obj->ecma, "Number", $t);
    
    return new Value($obj->ecma, "Number", DateFromTime($t));
  }
}

class DateGetDate extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = $obj->ToObject()->Value;
    if(is_nan($t))
      return new Value($obj->ecma, "Number", $t);
    return new Value($obj->ecma, "Number", DateFromTime(EcmaLocalTime($t)));
  }
}

class DateGetUTCMonth extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $value = $obj->ToObject()->Value;
    if(is_nan($value))
      return new Value($obj->ecma, "Number", $value);
    return new Value($obj->ecma, "Number", MonthFromTime($value));
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
function Day(int $t) : int{
  return floor($t / msPerDay);
}

function MonthFromTime($t) : int{
   $year = YearFromTime($t);
   $d = Day($t) - DayFromYear($year);

   $d -= 31 + 28;
   if($d < 0){
      return ($d < -28) ? 0 : 1;
   }

   if(IsLeapYear($year)){
      if($d == 0)
          return 1; // 29 February
            $d--;
   }

   // d: date count from 1 March
   $estimate = $d / 30; // approx number of month since March
   switch ($estimate) {
            case 0: return 2;
            case 1: $mstart = 31; break;
            case 2: $mstart = 31+30; break;
            case 3: $mstart = 31+30+31; break;
            case 4: $mstart = 31+30+31+30; break;
            case 5: $mstart = 31+30+31+30+31; break;
            case 6: $mstart = 31+30+31+30+31+31; break;
            case 7: $mstart = 31+30+31+30+31+31+30; break;
            case 8: $mstart = 31+30+31+30+31+31+30+31; break;
            case 9: $mstart = 31+30+31+30+31+31+30+31+30; break;
            case 10: return 11; //Late december
        }
        // if d < mstart then real month since March == estimate - 1
        return ($d >= $mstart) ? $estimate + 2 : $estimate + 1;
}

function DateFromTime($t){
   $year = YearFromTime($t);
   $d = Day($t) - DayFromYear($year);

   $d -= 31 + 28;
   if($d < 0){
      return ($d < -28) ? $d + 31 + 28 + 1 : $d + 28 + 1;
   }

   if(IsLeapYear($year)) {
      if($d == 0)
          return 29; // 29 February
            $d--;
   }
   // d: date count from 1 March
   switch(round($d / 30)) { // approx number of month since March
        case 0: return $d + 1;
        case 1: $mdays = 31; $mstart = 31; break;
        case 2: $mdays = 30; $mstart = 31+30; break;
        case 3: $mdays = 31; $mstart = 31+30+31; break;
        case 4: $mdays = 30; $mstart = 31+30+31+30; break;
        case 5: $mdays = 31; $mstart = 31+30+31+30+31; break;
        case 6: $mdays = 31; $mstart = 31+30+31+30+31+31; break;
        case 7: $mdays = 30; $mstart = 31+30+31+30+31+31+30; break;
        case 8: $mdays = 31; $mstart = 31+30+31+30+31+31+30+31; break;
        case 9: $mdays = 30; $mstart = 31+30+31+30+31+31+30+31+30; break;
        case 10: return $d - (31+30+31+30+31+31+30+31+30) + 1; //Late december
        }
        $d -= $mstart;
        if ($d < 0) {
            // wrong estimate: sfhift to previous month
            $d += $mdays;
        }
        return $d + 1;
}
