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
    $this->Put("abs",    new Property(new Value($ecma, "Object", new MathAbs())));
    $this->Put("acos",   new Property(new Value($ecma, "Object", new MathAcos())));
    $this->Put("asin",   new Property(new Value($ecma, "Object", new MathAsin())));
    $this->Put("atan",   new Property(new Value($ecma, "Object", new MathAtan())));
    $this->Put("atan2",  new Property(new Value($ecma, "Object", new MathAtan2())));
    $this->Put("ceil",   new Property(new Value($ecma, "Object", new MathCeil())));
    $this->Put("cos",    new Property(new Value($ecma, "Object", new MathCos())));
    $this->Put("exp",    new Property(new Value($ecma, "Object", new MathExp())));
    $this->Put("floor",  new Property(new Value($ecma, "Object", new MathFloor())));
    $this->Put("log",    new Property(new Value($ecma, "Object", new MathLog())));
    $this->Put("max",    new Property(new Value($ecma, "Object", new MathMax())));
    $this->Put("min",    new Property(new Value($ecma, "Object", new MathMin())));
    $this->Put("pow",    new Property(new Value($ecma, "Object", new MathPow())));
    $this->Put("random", new Property(new Value($ecma, "Object", new MathRandom())));
    $this->Put("round",  new Property(new Value($ecma, "Object", new MathRound())));
    $this->Pit("sin",    new Property(new Value($ecma, "Object", new MathSin())));
    $this->Put("sqrt",   new Property(new Value($ecma, "Object", new MathSqrt())));
    $this->Put("tan",    new Property(new Value($ecma, "Object", new MathTan())));
  }
}

class MathTan extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    return new Value($obj->ecma, "Number", tan($arg[0]->ToNumber()));
  }
}

class MathSqrt extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    return new Value($obj->ecma, "Number", sqrt($arg[0]->ToNumber()));
  }
}

class MathSin extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    return new Value($obj->ecma, "Number", sin($arg[0]->ToNumber()));
  }
}

class MathRound extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    return new Value($obj->ecma, "Number", round($arg[0]->ToNumber()));
  }
}

class MathRandom extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    return new Value($obj->ecma, "Number", rand(0, 1));
  }
}

class MathPow extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    return new Value($obj->ecma, "Number", pow($arg[0]->ToNumber()));
  }
}

class MathMin extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    return new Value($obj->ecma, "Number", min($arg[0]->ToNumber(), $arg[1]->ToNumber()));
  }
}

class MathMax extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    return new Value($obj->ecma, "Number", max($arg[0]->ToNumber(), $arg[1]->ToNumber()));
  }
}

class MathLog extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    return new Value($obj->ecma, "Number", log($arg[0]->ToNumber()));
  }
}

class MathFloor extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    return new Value($obj->ecma, "Number", floor($arg[0]->ToNumber()));
  }
}

class MathExp extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    return new Value($obj->ecma, "Number", exp($arg[0]->ToNumber()));
  }
}

class MathCos extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    return new Value($obj->ecma, "Number", cos($arg[0]->ToNumber()));
  }
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
