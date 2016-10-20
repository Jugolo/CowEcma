<?php
namespace Types\Objects\GlobelObject\Objects;

use Types\Objects\HeadObject\HeadObject;
use Types\Objects\Property\Property;
use Types\Value\Value;
use Types\Objects\Constructor\Constructor;
use Types\Objects\Call\Call;

class Objects extends HeadObject implements Constructor, Call{
  public function __construct(){
    $this->prototype = new HeadObject();
    $this->prototype->Put("constructor", new Property(new Value("Object", new ObjectPrototypeConstructor($this))));
    $this->prototype->Put("toString", new Property(new Value("Object", new ObjectPrototypeToString())));
    $this->prototype->Put("valueOf", new Property(new Value("Object", new ObjectPrototypeValueOf())));
  }

  public function Construct(array $arg) : Value{
    if(empty($arg) || $arg[0]->isNull() || $arg[0]->isUndefined()){
      $obj = new Objects();
      $obj->prototype = $this->prototype;
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
