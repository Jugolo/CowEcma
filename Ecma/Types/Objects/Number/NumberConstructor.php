<?php
namespace Ecma\Types\Objects\Number\NumberConstructor;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Objects\Constructor\Constructor;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Value\Value;
use Ecma\Ecma\Ecma;
use Ecma\Types\Objects\Number\NumberPrototype\NumberPrototype;

class NumberConstructor extends HeadObject implements Call, Constructor{
  protected $ecma;
  
  public function __construct(Ecma $ecma){
    $ecma->number = new NumberPrototype($ecma);
    $this->ecma = $ecma;
    
    $this->Put("prototype", new Property(new Value($ecma, "Object", $ecma->number)));
  }
  
  public function Call(Value $value, array $arg) : Value{
    if(count($arg) < 1)
      $number = 0;
    else
      $number = $arg[0]->ToNumber();
    
    return new Value($obj->ecma, "Number", $number);
  }
  
  public function Construct(array $arg){
    return new NumberInstance($this->ecma, count($arg) < 1 ? 0 : $arg[0]->ToNumber());
  }
}
