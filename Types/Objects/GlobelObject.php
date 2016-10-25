<?php
namespace Types\Objects\GlobelObject;

use Types\Objects\HeadObject\HeadObject;
use Types\Objects\GlobelObject\Objects\Objects;
use Types\Objects\Functions\FunctionConstructor\FunctionConstructor;
use Types\Objects\Property\Property;
use Types\Objects\GlobelObject\EcmaDate\EcmaDate;
use BuiltInFunction\BuiltInFunction;
use Types\Value\Value;

class GlobelObject extends HeadObject{
  public function __construct(\Ecma\Ecma $ecma){
    //append prototype to this object
    $this->prototype = new HeadObject();
    $this->Put("Object", new Property(new Value("Object", ($ecma->object = new Objects($ecma)))));
    $this->Put("Function", new Property(new Value("Object", new FunctionConstructor($ecma))));
  }
}
