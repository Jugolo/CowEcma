<?php
namespace Types\Objects\GlobelObject\Objects;

use Types\Objects\HeadObject\HeadObject;
use Types\Objects\Property\Property;
use Types\Value\Value;
use Types\Objects\Constructor\Constructor;
use Types\Objects\Call\Call;

class Objects extends HeadObject implements Constructor, Call{
  public function __construct(){
    $prototype = new HeadObject();
    $prototype->Put("constructor", new Property(new Value("Object", new ObjectPrototypeConstructor($this))));
    $prototype->Put("toString", new Property(new Value("Object", new ObjectPrototypeToString())));
    $prototype->Put("valueOf", new Property(new Value("Object", new ObjectPrototypeValueOf())));
    $this->Put("prototype", new Property(new Value("Object", $prototype)));
  }

  public function Construct(array $arg) : Value{
    if(empty($arg) || $arg[0]->isNull() || $arg[0]->isUndefined()){
      $obj = new Objects();
      $obj->Prototype = $this->Prototype;
      $obj->Class = "Object";
      return new Value("Object", $obj);
    }

    return new Value("Object", $arg[0]->ToObject());
  }

  public function Call($obj, array $arg) : Value{
    return new Value("Object", $this->Construct($arg));
  }
}

class ObjectPrototypeToString extends HeadObject{
  function Call($obj, array $arg) : Value{
    return "[Object ".$obj->Class."]";
  }
}

class ObjectPrototypeValueOf extends HeadObject{
  function Call($obj, array $arg) : Value{
    return new Value("Object", $obj->ToObject());
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
