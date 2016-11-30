<?php
namespace Ecma\Types\Objects\Boolean\BooleanConstructor;

use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Objects\Constructor;
use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Value\Value;
use Ecma\Ecma\Ecma;

class BooleanConstructor extends HeadObject implements Call, Constructor{
  protected $ecma;
  
  public function __construct(Ecma $ecma){
    $this->ecma = $ecma;
  }
  
  public function Call(Value $obj, array $arg) : Value{
    if(count($arg) < 1){
      return new Value($obj->ecma, "Boolean", false);
    }
    
    return new Value($obj->ecma, "Boolean", $arg[0]->ToBoolean());
  }
}
