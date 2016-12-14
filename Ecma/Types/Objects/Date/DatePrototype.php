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
