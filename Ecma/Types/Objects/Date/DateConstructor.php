<?php
namespace Ecma\Types\Objects\Date\DateConstructor;

use Ecma\Ecma\Ecma;
use Ecma\Types\Value\Value;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Objects\Constructor\Constructor;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Objects\Date\DatePrototype\DatePrototype;
use Ecma\Types\Objects\HeadObject\HeadObject;
use function Ecma\Types\Objects\Date\DatePrototype\EcmaTime;
use function Ecma\Types\Objects\Date\DatePrototype\YearFromTime;
use function Ecma\Types\Objects\Date\DatePrototype\MonthFromTime;
use function Ecma\Types\Objects\Date\DatePrototype\DateFromTime;
use function Ecma\Types\Objects\Date\DatePrototype\HourFromTime;
use function Ecma\Types\Objects\Date\DatePrototype\MinFromTime;
use function Ecma\Types\Objects\Date\DatePrototype\SecFromTime;
use function Ecma\Types\Objects\Date\DatePrototype\msFromTime;

class DateConstructor extends HeadObject implements Construtor, Call{
  protected $ecma;
  
  public function __construct(Ecma $ecma){
    $this->Prototype = $ecma->_function;
    $this->Put("prototype", new Property(new Value($ecma, "Object", new DatePrototype($ecma, $this)), ["DontEnum", "DontDelete", "ReadOnly"]));
    $this->Put("parse",     new Property(new Value($ecma, "Object", new DateParse($this))));
    $this->Put("UTC",       new Property(new Value($ecma, "Object", new DateUTC())));
  }
  
  public function Construct(array $arg) : Value{
    
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
    }
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
