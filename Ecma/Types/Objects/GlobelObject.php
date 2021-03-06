<?php
namespace Ecma\Types\Objects\GlobelObject;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\GlobelObject\Objects\Objects;
use Ecma\Types\Objects\Functions\FunctionConstructor\FunctionConstructor;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Objects\GlobelObject\EcmaDate\EcmaDate;
use Ecma\BuiltInFunction\BuiltInFunction;
use Ecma\Types\Value\Value;
use Ecma\Types\Objects\Arrays\ArrayConstructor\ArrayConstructor;
use Ecma\Ecma\Ecma;
use Ecma\Types\Objects\GlobelObject\Functions\Functions;
use Ecma\Types\Objects\String\StringConstructor\StringConstructor;
use Ecma\Types\Objects\Number\NumberConstructor\NumberConstructor;
use Ecma\Types\Objects\Boolean\BooleanConstructor\BooleanConstructor;
use Ecma\Types\Objects\Date\DateConstructor\DateConstructor;
use Ecma\Types\Objects\Math\Math\Math;

class GlobelObject extends HeadObject{
  public function __construct(Ecma $ecma){
    //append prototype to this object
    $this->prototype = new HeadObject();
    $this->Put("NaN", new Property(new Value($ecma, "Number", acos(8))));
    $this->Put("Infinity", new Property(new Value($ecma, "Number", INF)));
    $this->Put("Array", new Property(new Value($ecma, "Object", new ArrayConstructor($ecma))));
    $this->Put("Object", new Property(new Value($ecma, "Object", ($ecma->object = new Objects($ecma)))));
    $this->Put("Function", new Property(new Value($ecma, "Object", new FunctionConstructor($ecma))));
    $this->Put("String", new Property(new Value($ecma, "Object", new StringConstructor($ecma))));
    $this->Put("Boolean", new Property(new Value($ecma, "Object", new BooleanConstructor($ecma))));
    $this->Put("Number", new Property(new Value($ecma, "Object", new NumberConstructor($ecma))));
    $this->Put("Math", new Property(new Value($ecma, "Object", new Math($ecma))));
    $this->Put("Date", new Property(new Value($ecma, "Object", new DateConstructor($ecma))));
    new Functions($this, $ecma);
  }
}
