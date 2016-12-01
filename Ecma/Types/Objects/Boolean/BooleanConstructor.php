<?php
namespace Ecma\Types\Objects\Boolean\BooleanConstructor;

use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Objects\Constructor\Constructor;
use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Value\Value;
use Ecma\Ecma\Ecma;
use Ecma\Types\Objects\Boolean\BooleanPrototype\BooleanPrototype;
use Ecma\Types\Objects\Boolean\BooleanInstance\BooleanInstance;
use Ecma\Types\Objects\Property\Property;

class BooleanConstructor extends HeadObject implements Call, Constructor{
  protected $ecma;
  
  public function __construct(Ecma $ecma){
    $this->ecma = $ecma;
    $ecma->_boolean = new BooleanPrototype($ecma, $this);
    $this->Put("prototype", new Property(new Value($ecma, "Object", $ecma->_boolean)));
  }
  
  public function Call(Value $obj, array $arg) : Value{
    if(count($arg) < 1){
      return new Value($obj->ecma, "Boolean", false);
    }
    
    return new Value($obj->ecma, "Boolean", $arg[0]->ToBoolean());
  }
  
  public function Construct(array $arg) : Value{
    if($arg == 0)
      $value = false;
    else
      $value = $arg[0]->ToBoolean();
    
    return new Value($this->ecma, "Object", new BooleanInstance($this->ecma, $value));
  }
}
