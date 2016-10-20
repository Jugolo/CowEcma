<?php
namespace Types\Objects\Functions\FunctionInstance;

use Types\Value\Value;

class FunctionInstance extends HeadObject implements Constructor{

  public function __construct(){
    $this->Put("length", new Property(new Value("Number", 1)));
    $this->prototype = new HeadObject();
  }

  public function Construct(array $arg) : Value{

  }

  public function Call($obj, $args){

  }
}
