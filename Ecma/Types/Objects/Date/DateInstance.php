<?php
namespace Ecma\Types\Objects\Date\DateInstance;

use Ecma\Types\Objects\HeadObject\HeadObject;

class DateInstance extends HeadObject{
  public function __construct(DateConstructor $date, int $time){
    $this->Prototype = $date->Get("prototype")->GetValue()->ToObject();
    $this->Class = "Date";
    $this->Value = $time;
  }
}
