<?php
namespace Ecma\Types\Objects\Date\DateConstructor;

use Ecma\Ecma\Ecma;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Objects\Constructor\Constructor;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Objects\Date\DatePrototype\DatePrototype;

class DateConstructor extends HeadObject implements Construtor, Call{
  protected $ecma;
  
  public function __construct(Ecma $ecma){
    $this->Prototype = $ecma->_function;
    $this->Put("prototype", new Property(new Value($ecma, "Object", new DatePrototype($ecma, $this)), ["DontEnum", "DontDelete", "ReadOnly"]));
    
  }
  
  public function Construct(array $arg) : Value{
    
  }
  
  public function Call(Value $obj, array $arg){
    return new Value($obj->ecma, "String", $this->Construct([])->ToString());
  }
}
