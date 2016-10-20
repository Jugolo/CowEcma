<?php
namespace Types\Objects\Functions\FunctionConstructor;

use Types\Objects\HeadObject\HeadObject;
use Types\Objects\Constructor\Constructor;
use Types\Value\Value;
use Types\Objects\Property\Property;
use Types\Objects\Functions\FunctionPrototype\FunctionPrototype;

class FunctionConstructor extends HeadObject implements Constructor{

  public function __construct(){
    $this->Put("prototype", new Property(new Value("Object", new FunctionPrototype($this)), ["DontEnum", "DontDelete", "ReadOnly"]));
    $this->Put("length", new Property(new Value("Object", 1)));
  }

  public function Construct(array $arg) : Value{

  }
}
