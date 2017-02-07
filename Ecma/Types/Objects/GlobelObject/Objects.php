<?php
namespace Ecma\Types\Objects\GlobelObject\Objects;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Value\Value;
use Ecma\Types\Objects\Constructor\Constructor;
use Ecma\Types\Objects\Call\Call;
use Ecma\Ecma\Ecma;

class Objects extends HeadObject implements Constructor, Call{
  protected $ecma;
  public function __construct(Ecma $ecma){
    $this->ecma = $ecma;
    $prototype = new HeadObject();
    $prototype->Put("constructor", new Property(new Value($ecma, "Object", new ObjectPrototypeConstructor($this))));
    $prototype->Put("toString", new Property(new Value($ecma, "Object", new ObjectPrototypeToString())));
    $prototype->Put("valueOf", new Property(new Value($ecma, "Object", new ObjectPrototypeValueOf())));
    $this->Put("prototype", new Property(new Value($ecma, "Object", $prototype)));
  }

  public function Construct(array $arg) : Value{
    if(empty($arg) || $arg[0]->isNull() || $arg[0]->isUndefined()){
      $obj = new Objects();
      $obj->Prototype = $this->Prototype;
      $obj->Class = "Object";
      return new Value($this->ecma, "Object", $obj);
    }

    return new Value($this->ecma, "Object", $arg[0]->ToObject());
  }

  public function Call(Value $obj, array $arg) : Value{
    if(count($arg) != 0){
      if(!$arg[0]->isNull() && !$arg[0]->isUndefined()){
        return new Value($this->ecma, "Object", $arg[0]->ToObject());
      }
    }
    return new Value($this->ecma, "Object", $this->Construct($arg));
  }
}

class ObjectPrototypeToString extends HeadObject{
  function Call(Value $obj, array $arg) : Value{
    return "[Object ".$obj->Class."]";
  }
}

class ObjectPrototypeValueOf extends HeadObject{
  function Call(Value $obj, array $arg) : Value{
    return new Value($obj->ecma, "Object", $obj->ToObject());
  }
}

class ObjectPrototypeConstructor extends HeadObject implements Constructor{
  private $object;

  public function __construct(Objects $obj){
    $this->object = $obj;
  }

  public function Construct(array $arg) : Value{
    return $this->object->Construct($arg);
  }
}
