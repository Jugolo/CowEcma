<?php
namespace Ecma\Types\Objects\Date\DateConstructor;

use Ecma\Types\Objects\Constructor\Constructor;

class DateConstructor extends HeadObject implements Construtor, Call{
  public function Construct(array $arg) : Value{
    
  }
  
  public function Call(Value $obj, array $arg){
    return new Value($obj->ecma, "String", $this->Construct([])->ToString());
  }
}
