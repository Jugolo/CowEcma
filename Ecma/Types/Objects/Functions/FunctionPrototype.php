<?php
namespace Ecma\Types\Objects\Functions\FunctionPrototype;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Functions\FunctionConstructor\FunctionConstructor;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Value\Value;

class FunctionPrototype extends HeadObject{
  public function __construct(FunctionConstructor $func){
    $this->Class = "Function";
    $this->Prototype = new HeadObject();
    $this->Put("constructor", new Property(new Value("Object", $func)));
    $this->Put("toString", new Property(new Value("Object", new FunctionPrototypeToString())));
  }
}

class FunctionPrototypeToString extends HeadObject{
  public function Call($object, $args){
    return "function(){}";
  }
}
