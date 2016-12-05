<?php
namespace Ecma\Types\Objects\Math\Math;

use Ecma\Ecma\Ecma;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Value\Value;

class Math extends HeadObject{
  protected $ecma;
  
  public function __construct(Ecma $ecma){
    $this->ecma = $ecma;
    $this->Put("abs", new Property(new Value($ecma, "Object", new MathAbs())));
    $this->Put("acos", new Property(new Value($ecma, "Object", new MathAcos())));
  }
}

class MathAcos extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $number = $arg[0]->ToNumber();
    if(is_nan($number))
      return $arg[0];
    
    if($number > 1 || $number < -1)
      return new Value($obj->ecma, "Number", acos(8));
    
    if($number == 1)
      return new Value($obj->ecma, "Number", 0);
    
    return new Value($obj->ecma, "Number", acos($number));
  }
}

class MathAbs extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $number = $arg[0]->ToNumber();
    if(is_nan($number) || $number == 0)
      return $arg[0];
    return new Value($obj->ecma, "Number", +$number);
  }
}
