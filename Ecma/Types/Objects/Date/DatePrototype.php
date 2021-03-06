<?php
namespace Ecma\Types\Objects\Date\DatePrototype;

use Ecma\Types\Objects\HeadObject\HeadObject;
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
    $this->Put("constructor",        new Property(new Value($ecma, "Object", $constructor)));
    $this->Put("toString",           new Property(new Value($ecma, "Object", new DateToString())));
    $this->Put("valueOf",            new Property(new Value($ecma, "Object", new DateValueOf())));
    $this->Put("getTime",            new Property(new Value($ecma, "Object", new DateGetTime())));
    $this->Put("getYear",            new Property(new Value($ecma, "Object", new DateGetYear())));
    $this->Put("getFullYear",        new Property(new Value($ecma, "Object", new DateGetFullYear())));
    $this->Put("getUTCFullYear",     new Property(new Value($ecma, "Object", new DateGetUTCFullYear())));
    $this->Put("getMonth",           new Property(new Value($ecma, "Object", new DateGetMonth())));
    $this->Put("getUTCMonth",        new Property(new Value($ecma, "Object", new DateGetUTCMonth())));
    $this->Put("getDate",            new Property(new Value($ecma, "Object", new DateGetDate())));
    $this->Put("getUTCDate",         new Property(new Value($ecma, "Object", new DateGetUTCDate())));
    $this->Put("getDay",             new Property(new Value($ecma, "Object", new DateGetDay())));
    $this->Put("getUTCDay",          new Property(new Value($ecma, "Object", new DateGetUTCDay())));
    $this->Put("getHours",           new Property(new Value($ecma, "Object", new DateGetHours())));
    $this->Put("getUTCHours",        new Property(new Value($ecma, "Object", new DateGetUTCHours())));
    $this->Put("getMinutes",         new Property(new Value($ecma, "Object", new DateGetMinutes())));
    $this->Put("getUTCMinutes",      new Property(new Value($ecma, "Object", new DateGetUTCMinutes())));
    $this->put("getSeconds",         new Property(new Value($ecma, "Object", new DateGetSeconds())));
    $this->Put("getUTCSeconds",      new Property(new Value($ecma, "Object", new DateGetUTCSeconds())));
    $this->Put("getMilliseconds",    new Property(new Value($ecma, "Object", new DateGetMilliseconds())));
    $this->Put("getUTCMilliseconds", new Property(new Value($ecma, "Object", new DateGetUTCMilliseconds())));
    $this->Put("getTimezoneOffset",  new Property(new Value($ecma, "Object", new DateGetTimezoneOffset())));
    $this->Put("setTime",            new Property(new Value($ecma, "Object", new DateSetTime())));
    $this->Put("setMilliseconds",    new Property(new Value($ecma, "Object", new DateSetMilliseconds())));
    $this->Put("setUTCMilliseconds", new Property(new Value($ecma, "Object", new DateSetUTCMilliseconds())));
    $this->Put("setSeconds",         new Property(new Value($ecma, "Object", new DateSetSeconds())));
    $this->Put("setUTCSeconds",      new Property(new Value($ecma, "Object", new DateSetUTCSeconds())));
    $this->Put("setMinutes",         new Property(new Value($ecma, "Object", new DateSetMinutes())));
    $this->Put("setUTCMinutes",      new Property(new Value($ecma, "Object", new DateSetUTCMinutes())));
    $this->Put("setHours",           new Property(new Value($ecma, "Object", new DateSetHours())));
    $this->Put("setUTCHours",        new Property(new Value($ecma, "Object", new DateSetUTCHours())));
    $this->Put("setDate",            new Property(new Value($ecma, "Object", new DateSetDate())));
    $this->Put("setUTCDate",         new Property(new Value($ecma, "Object", new DateSetUTCDate())));
    $this->Put("setMonth",           new Property(new Value($ecma, "Object", new DateSetMonth())));
    $this->Put("setUTCMonth",        new Property(new Value($ecma, "Object", new DateSetUTCMonth())));
    $this->Put("setFullYear",        new Property(new Value($ecma, "Object", new DateSetFullYear())));
    $this->Put("setUTCFullYear",     new Property(new Value($ecma, "Object", new DateSetUTCFullYear())));
    $this->Put("setYear",            new Property(new Value($ecma, "Object", new DateSetYear())));
    $this->Put("toLocaleString",     new Property(new Value($ecma, "Object", new DateToLocaleString())));
    $this->Put("toUTCString",        new Property(new Value($ecma, "Object", new DateToLocaleString())));
    $this->Put("toGMTString",        new Property(new Value($ecma, "Object", new DateToLocaleString())));
  }
}

class DateToLocaleString extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    return $obj->ToObject()->Get("toString")->GetValue()->Call($obj, $value);
  }
}

class DateSetYear extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $value = $obj->ToObject()->Value;
    $t = is_nan($value) ? 0 : $value;
    $year = $arg[0]->ToNumber();
    if(is_nan($year)){
      $obj->ToObject()->Value = $year;
      return $arg[0];
    }
    
    if($year > 0 && $year <= 99){
      $year += 1900;
    }
    
    return new Value(
      $obj->ecma,
      "Number",
      $obj->ToObject()->Value = TimeClip(
        UTC(
          MakeDate(
            MakeDay(
              $year,
              MonthFromTime($t),
              DateFromTime($t)
              ),
            TimeWithinDay($t)
            )
          )
        )
      );
  }
}

class DateSetUtcFullYear extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $value = $obj->ToObject()->Value;
    $t = is_nan($value) ? 0 : EcmaLocalTime($value);
    return new Value(
      $obj->ecma,
      "Number",
      $obj->ToObject()->Value = TimeClip(
        MakeDate(
          MakeDay(
            $arg[0]->ToNumber(),
            count($arg) >= 2 ? $arg[1]->ToNumber() : MonthFromTime($t),
            count($arg) >= 3 ? $arg[2]->ToNumber() : DateFromTime($t)
            ),
          TimeWithinDay($t)
          )
        )
      );
  }
}

class DateSetFullYear extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $value = $obj->ToObject()->ToNumber();
    $t = is_nan($value) ? 0 : EcmaLocalTime($value);
    $year = $arg[0]->ToNumber();
    $month = count($arg) >= 2 ? $arg[1]->ToNumber() : MonthFromTime($t);
    $date = count($arg) >= 3 ? $arg[2]->ToNumber() : DateFromTime($t);
    $obj->ToObject()->Value = TimeClip(
      UTC(
        MakeDate(
          MakeDay(
            $year,
            $month,
            $date
            ),
          TimeWithinDay($t)
          )
        )
      );
    return new Value($obj->ecma, "Number", $obj->ToObject()->Value);
  }
}

class DateSetUTCMonth extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = EcmaLocalTime($obj->ToObject()->Value);
    $mon = $arg[0]->ToNumber();
    $date = count($arg) >= 2 ? $arg[1]->ToNumber() : DateFromTime($t);
    $obj->ToObject()->Value = TimeClib(
      MakeDate(
        MakeDay(
          YearFromTime($t),
          $mon,
          $date
          ),
        TimeWithinDay($t)
        )
      );
    return new Value($obj->ecma, "Number", $obj->ToObject()->Value);
  }
}

class DateSetMonth extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = EcmaLocalTime($obj->ToObject()->Value);
    $mon = $arg[0]->ToNumber();
    $date = count($arg) >= 2 ? $arg[1]->ToNumber() : DateFromTime($t);
    $obj->ToObject()->Value = TimeClip(
      UTC(
        MakeDate(
          MakeDay(
            YearFromTime($t),
            $mon,
            $date
            ),
          TimeWithinDay($t)
          )
        )
      );
    return new Value($obj->ecma, "Number", $obj->ToObject()->Value);
  }
}

class DateSetUTCDate extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = EcmaLocalTime($obj->ToObject()->Value);
    $day = MakeDay(
      YearFromTime($t),
      MonthFromTime($t),
      $arg[0]->ToNumber()
      );
    $obj->ToObject()->Value = TimeClip(
      MakeDate(
        $day,
        TimeWithinDay($t)
        )
      );
    return new Value($obj->ecma, "Number", $obj->ToObject()->Value);
  }
}

class DateSetDate extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = EcmaLocalTime($obj->ToObject()->Value);
    $day = MakeDay(
      YearFromTime($t),
      MonthFromTime($t),
      $arg[0]->ToNumber()
      );
    $obj->ToObject()->Value = TimeClip(
      UTC(
        MakeDate(
          $day,
          TimeWithinDay($t)
          )
        )
      );
    return new Value($obj->ecma, "Number", $obj->ToObject()->Value);
  }
}

class DateSetUTCHours extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = EcmaLocalTime($obj->ToObject()->Value);
    $hour = $arg[0]->ToNumber();
    $min = count($arg) >= 2 ? $arg[1]->ToNumber() : MinFromTime($t);
    $sec = count($arg) >= 3 ? $arg[2]->ToNumber() : SecFromTime($t);
    $ms = count($arg) >= 4 ? $arg[3]->ToNumber() : msFromTime($t);
    $obj->ToObject()->Value = TimeClip(
      MakeDate(
        Day($t),
        MakeTime(
          $hour,
          $min,
          $sec,
          $ms
          )
        )
      );
    return new Value($obj->ecma, "Number", $obj->ToObject()->Value);
  }
}

class DateSetHours extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = EcmaLocalTime($obj->ToObject()->Value);
    $hour = $arg[0]->ToNumber();
    $min = count($arg) >= 2 ? $arg[1]->ToNumber() : MinFromTime($t);
    $sec = count($arg) >= 3 ? $arg[2]->ToNumber() : SecFromTime($t);
    $ms = count($arg) >= 4 ? $arg[3]->ToNumber() : msFromTime($t);
    $obj->ToObject()->Value = TimeClip(
      UTC(
        MakeDate(
          Day($t),
          MakeTime(
            $hour,
            $min,
            $sec,
            $ms
            )
          )
        )
      );
    return new Value($obj->ecma, "Number", $obj->ToObject()->Value);
  }
}

class DateSetUTCMinutes extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = EcmaLocalTime($obj->ToObject()->Value);
    $min = $arg[0]->ToNumber();
    $sec = count($arg) >= 2 ? $arg[1]->ToNumbet() : SecFromTime($t);
    $ms = count($arg) >= 3 ? $arg[2]->ToNumber() : msFromTime($t);
    $obj->ToObject()->Value = TimeClip(
      MakeDate(
        Day($t),
        MakeTime(
          HourFromTime($t),
          $min,
          $sec,
          $ms
          )
        )
      );
    return new Value($obj->ecma, "Number", $obj->ToObject()->Value);
  }
}

class DateSetMinutes extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = EcmaLocalTime($obj->ToObject()->Value);
    $min = $arg[0]->ToNumber();
    $sec = count($arg) >= 2 ? $arg[1]->ToNumber() : SecFromTime($t);
    $ms  = count($arg) >= 3 ? $arg[2]->ToNumber() : msFromTime($t);
    $obj->ToObject()->Value = TimeClip(
      UTC(
        MakeDate(
          Day($t),
          MakeTime(
            HourFromTime($t),
            $min,
            $sec,
            $ms
            )
          )
        )
      );
    return new Value($obj->ecma, "Number", $obj->ToObject()->Value);
  }
}

class DateSetUTCSeconds extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = $obj->ToObject()->Value;
    $sec = $arg[0]->ToNumber();
    $ms = count($arg) >= 2 ? $arg[1]->ToNumber() : msFromTime($t);
    $obj->ToObject()->Value = TimeClib(
        MakeDate(
          Day($t),
          MakeTime(
            HourFromTime($t),
            MinFromTime($t),
            $sec,
            $ms
            )
        )
      );
    return new Value($obj->ecma, "Number", $obj->ToObject()->Value);
  }
}

class DateSetSeconds extends HeadObject implements Call{
 public function Call(Value $obj, array $arg) : Value{
   $t = EcmaLocalTime($obj->ToObject()->Value);
   $sec = $arg[0]->ToNumber();
   $ms = count($arg) >= 2 ? $arg[1]->ToNumber() : msFromTime($t);
   $obj->ToObject()->Value = TimeClib(
     UTC(
       MakeDate(
         Day($t),
         MakeTime(
           HourFromTime($t),
           MinFromTime($t),
           $sec,
           $ms
           )
         )
       )
     );
   return new Value($obj->ecma, "Number", $obj->ToObject()->Value);
 }
}

class DateSetUTCMilliseconds extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $ms = $arg[0]->ToNumber();
    $t = $obj->ToObject()->Value;
    $time = MakeTime(
      HourFromTime($t),
      MinFromTime($t),
      SecFromTime($t),
      $ms
      );
    $obj->ToObject()->Value = TimeClip(
      MakeDate(
        Day($t),
        $time
        )
      );
    
    return new Value($obj->ecma, "Number", $obj->ToObject()->Value);
  }
}

class DateSetMilliseconds extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = EcmaLocalTime($obj->ToObject()->Value);
    $time = MakeTime(
      HourFromTime($t),
      MinFromTime($t),
      SecFromTime($t),
      $arg[0]->ToNumber()
      );
    
    $utc = UTC(
      MakeDate(
        Day($t),
        $time
        )
      );
    $o = $obj->ToObject();
    $o->Value = TimeClip($utc);
    return new Value($obj->ecma, "Number", $o->Value);
  }
}

class DateSetTime extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $o = $obj->ToObject();
    if(!($o instanceof DateInstance))
      throw new RuntimeException("Date.setTime should be method of Date instance");
    $o->Value = TimeClip($arg[0]->ToNumber());
    return new Value($obj->ecma, "Number", $o->Value);
  }
}

class DateGetTimezoneOffset extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = $obj->ToObject()->Value;
    if(is_nan($t))
      return new Value($obj->ecma, "Number", $t);
    return new Value($obj->ecma, "Number", ($t - EcmaLocalTime($t)) / msPerMinute);
  }
}

class DateGetUTCMilliseconds extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = $obj->ToObject()->Value;
    if(is_nan($t))
      return new Value($obj->ecma, "Number", $t);
    return new Value($obj->ecma, "Number", msFromTime($t));
  }
}

class DateGetMilliseconds extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = $obj->ToObject()->Value;
    if(is_nan($t))
      return new Value($obj->ecma, "Number", $t);
    return new Value($obj->ecma, "Number", msFromTime(EcmaLocalTime($t)));
  }
}

class DateGetUTCSeconds extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = $obj->ToObject()->Value;
    if(is_nan($t))
      return new Value($obj->ecma, "Number", $t);
    return new Value($obj->ecma, "Number", SecFromTime($t));
  }
}

class DateGetSeconds extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = $obj->ToObject()->Value;
    if(is_nan($t))
      return new Value($obj->ecma, "Number", $t);
    return new Value($obj, "Number", SecFromTime(EcmaLocalTime($t)));
  }
}

class DateGetUTCMinutes extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = $obj->ToObject()->Value;
    if(is_nan($t))
      return new Value($obj->ecma, "Number", $t);
    
    return new Value($obj->ecma, "Number", MinFromTime($t));
  }
}

class DateGetMinutes extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = $obj->ToObject()->Value;
    if(is_nan($t))
      return new Value($obj->ecma, "Number", $t);
    return new Value($obj->ecma, "Number", MinFromTime(EcmaLocalTime($t)));
  }
}

class DateGetUTCHours extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = $obj->ToObject()->Value;
    if(is_nan($t))
      return new Value($obj->ecma, "Number", $t);
    return new Value($obj->ecma, "Number", HourFromTime($t));
  }
}

class DateGetHours extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = $obj->ToObject()->Value;
    if(is_nan($t))
      return new Value($obj->ecma, "Number", $t);
    return new Value($obj->ecma, "Number", HourFromTime(EcmaLocalTime($t)));
  }
}

class DateGetUTCDay extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = $obj->ToObject()->Value;
    if(is_nan($t))
      return new Value($obj->ecma, "Number", $t);
    return new Value($obj->ecma, "Number", WeekDay($t));
  }
}

class DateGetDay extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $t = $obj->ToObject()->Value;
    if(is_nan($t))
      return new Value($obj->ecma, "Number", $t);
    return new Value($obj->ecma, "Number", WeekDay(EcmaLocalTime($t)));
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

function TimeWithinDay(int $t) : int{
  return $t / msPerDay;
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

function WeekDay(int $t) : int{
   $result = Day($t) + 4;
   $result = $result % 7;
   if($result < 0)
     $result += 7;
    return $result;
}

function HourFromTime(int $t) : int{
  return floor($t / msPerHour) % HoursPerDay;
}

function MinFromTime(int $t) : int{
  return floor($t / msPerMinute) % MinutesPerHour;
}

function SecFromTime(int $t) : int{
  return floor($t / msPerSecond) % SecondsPerMinute;
}

function msFromTime(int $t) : int{
  return $t % msPerSecond;
}

function TimeClip(float $time){
  if(!is_finite($time))
    return acos(8);
  
  if($time > 8.64e15)
    return acos(8);
  
  return $time;
}

function MakeTime(int $hour, int $min, int $sec, int $ms) : int{
  return ($hour * msPerHour) + ($min * msPerMinute) + ($sec * msPerSecond) + $ms;
}

function MakeDate($day, $time){
  if(!is_finite($day))
    return NAN;
  
  return $day * msPerDay + $time;
}

function MakeDay(int $year, int $month, int $date){
  if(!is_finite($year) || !is_finite($month) || !is_finite($date)){
    return NAN;
  }
  
  $year += floor($month / 12);
  $month = $month % 12;
        
  if($month < 0)
    $month += 12;

   $yearday = floor(TimeFromYear($year) / msPerDay);
   $monthday = DayFromMonth($month, $year);

   return $yearday + $monthday + $date - 1;
}

function UTC($t){
  $localtza = new DateTime();
  return $t - $localtza->getOffset() - DaylightSavingTA($t - $localtza->getOffset());
}
