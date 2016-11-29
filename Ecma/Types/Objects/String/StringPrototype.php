<?php
namespace Ecma\Types\Objects\String\StringPrototype\StringPrototype;

use Ecma\Ecma\Ecma;
use Ecma\Types\Value\Value;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\String\StringConstructor\StringConstructor;

class StringPrototype extends HeadObject{
  public function __construct(StringConstructor $constructor, Ecma $ecma){
    $this->Put("constructor", new Property(new Value($ecma, "Object", $constructor)));
  }
}
