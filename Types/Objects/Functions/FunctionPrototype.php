<?php
namespace Types\Objects\Functions\FunctionPrototype;

use Types\Objects\HeadObject\HeadObject;
use Types\Objects\Functions\FunctionConstructor\FunctionConstructor;
use Types\Objects\Property\Property;
use Types\Value\Value;

class FunctionPrototype extends HeadObject{
  public function __construct(FunctionConstructor $func){
    $this->Class = "Function";
    $this->prototype = new HeadObject();
    $this->Put("constructor", new Property(new Value("Object", $func)));
    $this->Put("toString", new Property(new Value("Object", new FunctionPrototypeToString())));
  }
}

class FunctionPrototypeToString extends HeadObject{
  public function Call($object, $args){
    return "function(){}";
  }
}
