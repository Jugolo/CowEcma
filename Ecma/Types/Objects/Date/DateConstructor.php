<?php
namespace Ecma\Types\Objects\Date\DateConstructor;

use Ecma\Ecma\Ecma;
use Ecma\Types\Value\Value;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Objects\Constructor\Constructor;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Objects\Date\DatePrototype\DatePrototype;
use Ecma\Types\Objects\Date\DateInstance\DateInstance;
use Ecma\Types\Objects\HeadObject\HeadObject;
use function Ecma\Types\Objects\Date\DatePrototype\EcmaTime;
use function Ecma\Types\Objects\Date\DatePrototype\YearFromTime;
use function Ecma\Types\Objects\Date\DatePrototype\MonthFromTime;
use function Ecma\Types\Objects\Date\DatePrototype\DateFromTime;
use function Ecma\Types\Objects\Date\DatePrototype\HourFromTime;
use function Ecma\Types\Objects\Date\DatePrototype\MinFromTime;
use function Ecma\Types\Objects\Date\DatePrototype\SecFromTime;
use function Ecma\Types\Objects\Date\DatePrototype\msFromTime;
use function Ecma\Types\Objects\Date\DatePrototype\MakeDay;
use function Ecma\Types\Objects\Date\DatePrototype\TimeClip;
use function Ecma\Types\Objects\Date\DatePrototype\MakeDate;
use function Ecma\Types\Objects\Date\DatePrototype\MakeTime;
use function Ecma\Types\Objects\Date\DatePrototype\UTC;

class DateConstructor extends HeadObject implements Constructor, Call{
  protected $ecma;
  
  public function __construct(Ecma $ecma){
    $this->Prototype = $ecma->_function;
    $this->Put("prototype", new Property(new Value($ecma, "Object", new DatePrototype($ecma, $this)), ["DontEnum", "DontDelete", "ReadOnly"]));
    $this->Put("parse",     new Property(new Value($ecma, "Object", new DateParse($this))));
    $this->Put("UTC",       new Property(new Value($ecma, "Object", new DateUTC())));
  }
  
  public function Construct(array $arg) : Value{
    $size = count($arg);
    $time = EcmaTime();
    $year = $size >= 1 ? $arg[0]->ToNumber() : YearFromTime($time);
    return new DateInstance(
      $this,
      TimeClip(
        UTC(
          MakeDate(
            MakeDay(
              !is_nan($year) && 0 <= $year && $year <= 99 ? 1900+$year : $year,
              $size >= 2 ? $arg[1]->ToNumber() : MonthFromTime($time),
              $size >= 3 ? $arg[2]->ToNumber() : DateFromTime($time)
              ),
            MakeTime(
              $size >= 4 ? $arg[3]->ToNumber() : HourFromTime($time),
              $size >= 5 ? $arg[4]->ToNumber() : MinFromTime($time),
              $size >= 6 ? $arg[5]->ToNumber() : SecFromTime($time),
              $size >= 7 ? $arg[6]->ToNumber() : msFromTime($time)
              )
            )
          )
        )
      );
  }
  
  public function Call(Value $obj, array $arg){
    return new Value($obj->ecma, "String", $this->Construct([])->ToString());
  }
}

class DateUTC extends HeadObject implements Call{
  public function Call(Value $obj, array $arg){
    $size = count($arg);
    $time = EcmaTime();
    if($size == 0){
      $year   =  YearFromTime($time);
      $month  = MonthFromTime($time);
      $date   = DateFromTime($time);
      $hours  = HourFromTime($time);
      $minut  = MinFromTime($time);
      $second = SecFromTime($time);
      $ms     = msFromTime($time);
    }elseif($size == 1){
      $year   = $arg[0]->ToNumber();
      $month  = MonthFromTime($time);
      $date   = DateFromTime($time);
      $hours  = HourFromTime($time);
      $minut  = MinFromTime($time);
      $second = SecFromTime($time);
      $ms     = msFromTime($time);
    }elseif($size == 2){
      $year   = $arg[0]->ToNumber();
      $month  = $arg[1]->ToNumber();
      $date   = DateFromTime($time);
      $hours  = HourFromTime($time);
      $minut  = MinFromTime($time);
      $second = SecFromTime($time);
      $ms     = msFromTime($time);
    }elseif($size == 3){
      $year   = $arg[0]->ToNumber();
      $month  = $arg[1]->ToNumber();
      $date   = $arg[2]->ToNumber();
      $hours  = HourFromTime($time);
      $minut  = MinFromTime($time);
      $second = SecFromTime($time);
      $ms     = msFromTime($time);
    }elseif($size == 4){
      $year   = $arg[0]->ToNumber();
      $month  = $arg[1]->ToNumber();
      $date   = $arg[2]->ToNumber();
      $hours  = $arg[3]->ToNumber();
      $minut  = MinFromTime($time);
      $second = SecFromTime($time);
      $ms     = msFromTime($time);
    }elseif($size == 5){
      $year   = $arg[0]->ToNumber();
      $month  = $arg[1]->ToNumber();
      $date   = $arg[2]->ToNumber();
      $hours  = $arg[3]->ToNumber();
      $minut  = $arg[4]->ToNumber();
      $second = SecFromTime($time);
      $ms     = msFromTime($time);
    }elseif($size == 6){
      $year   = $arg[0]->ToNumber();
      $month  = $arg[1]->ToNumber();
      $date   = $arg[2]->ToNumber();
      $hours  = $arg[3]->ToNumber();
      $minut  = $arg[4]->ToNumber();
      $second = $arg[5]->ToNumber();
      $ms     = msFromTime($time);
    }else{
      $year   = $arg[0]->ToNumber();
      $month  = $arg[1]->ToNumber();
      $date   = $arg[2]->ToNumber();
      $hours  = $arg[3]->ToNumber();
      $minut  = $arg[4]->ToNumber();
      $second = $arg[5]->ToNumber();
      $ms     = $arg[6]->ToNumber();
    }
    
    if(!is_nan($year) && 0 <= $year && $year <= 99){
      $step8 = 1900+$year;
    }else{
      $step8 = $year;
    }
    
    return new Value(
      $obj->ecma,
      "Number",
      TimeClip(
        MakeDate(
          MakeDay(
            $step8,
            $month,
            $date
            ),
          MakeTime(
            $hours,
            $minut,
            $second,
            $ms
            )
          )
        )
      );
  }
}

class DateParse extends HeadObject implements Call{
  private $date;
  
  public function __construct(DateConstructor $date){
    $this->date = $date;
  }
  
  public function Call(Value $obj, array $arg) : Value{
    return $this->date->Construct([new Value($obj->ecma, "Number", strtotime($arg[0]->ToString())*1000)]);
  }
}
