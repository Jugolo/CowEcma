<?php
namespace Ecma\Types\Objects\Functions\FunctionPrototype;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Functions\FunctionConstructor\FunctionConstructor;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Value\Value;
use Ecma\Ecma\Ecma;

class FunctionPrototype extends HeadObject{
  protected $ecma;

  public function __construct(FunctionConstructor $func, Ecma $ecma){
    $this->Class = "Function";
    $this->Prototype = new HeadObject();
    $this->Put("constructor", new Property(new Value($ecma, "Object", $func)));
    $this->Put("toString", new Property(new Value($ecma, "Object", new FunctionPrototypeToString())));
  }
}

class FunctionPrototypeToString extends HeadObject{
  public function Call(Value $obj, $args){
    if($obj->ToObject()->Class == "Function"){
      throw new \RuntimeException("Function.prototype.toString() must be method of function object");
    }
    return "function(){}";
  }
}
