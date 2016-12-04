<?php
namespace Ecma\Types\Objects\Number\NumberConstructor;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Value\Value;
use Ecma\Ecma\Ecma;

class NumberConstructor extends HeadObject implements Call{
  protected $ecma;
  
  public function __construct(Ecma $ecma){
    $this->ecma = $ecma;
  }
  
  public function Call(Value $value, array $arg) : Value{
    if(count($arg) < 1)
      $number = 0;
    else
      $number = $arg[0]->ToNumber();
    
    return new Value($obj->ecma, "Number", $number);
  }
}
