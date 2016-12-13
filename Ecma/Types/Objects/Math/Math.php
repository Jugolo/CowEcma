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
    $this->Put("abs",   new Property(new Value($ecma, "Object", new MathAbs())));
    $this->Put("acos",  new Property(new Value($ecma, "Object", new MathAcos())));
    $this->Put("asin",  new Property(new Value($ecma, "Object", new MathAsin())));
    $this->Put("atan",  new Property(new Value($ecma, "Object", new MathAtan())));
    $this->Put("atan2", new Property(new Value($ecma, "Object", new MathAtan2())));
    $this->Put("ceil",  new Property(new Value($ecma, "Object", new MathCeil())));
    $this->Put("cos",   new Property(new Value($ecma, "Object", new MathCos())));
  }
}

class MathCos extends HeadObject implements Call{
  
}

class MathCeil extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    return new Value($obj->ecma, "Number", ceil($arg[0]->ToNumber()));
  }
}

class MathAtan2 extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $y = $arg[0]->ToNumber();
    $x = $arg[1]->ToNumber();
    
    if(is_nan($y) || is_nan($x))
       return new Value($obj->ecma, "Number", acos(8));
    
    if($y > 0 && $x == 0){
      return new Value($obj->ecma, "Number", M_PI/2);
    }
    
    if($y == 0 && $x == 0)
      return new Value($obj->ecma, "Number", 0);
    
    if($y == 0 && $x < 0)
      return new Value($obj->ecma, "Number", M_PI);
    
    if($y == 0 && $x > 0)
      return new Value($obj->ecma, "Number", 0);
    
    return new Value($obj->ecma, "Number", atan2($y, $x));
  }
}

class MathAtan extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $number = $arg[0]->ToNumber();
    if(is_nan($number) || $number == 0)
      return $number;
    
    if($number == INF){
      return M_PI/2;
    }
    
    if($number == -INF){
      return -(M_PI/2);
    }
    
    return new Value($obj->ecma, "Number", atan($number));
  }
}

class MathAsin extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $number = $arg[0]->ToNumber();
    if(is_nan($number))
      return $arg[0];
    
    if($number < -1 || $number > 1)
      return new Value($obj->ecma, "Number", acos(8));
    
    if($number == 0)
      return new Value($obj->ecma, "Number", 0);
    
    return new Value($obj->ecma, "Number", asin($number));
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
