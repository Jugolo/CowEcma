<?php
namespace Ecma\Types\Objects\Arrays\ArrayConstructor;

use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Constructor\Constructor;
use Ecma\Types\Value\Value;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Objects\Arrays\ArrayPrototype\ArrayPrototype;
use Ecma\Types\Objects\Arrays\ArrayInstance\ArrayInstance;
use Ecma\Ecma\Ecma;

class ArrayConstructor extends HeadObject implements Call, Constructor{
  private $proto;
  protected $ecma;

  public function __construct(Ecma $ecma){
    $this->proto = new ArrayPrototype($this, $ecma);
    $this->ecma = $ecma;
  }
  public function Call(Value $obj, array $args) : Value{
    return $this->Construct($args);
  }

  public function Construct(array $args) : Value{
    $array = new ArrayInstance();
    $array->Prototype = $this->proto;
    $array->Class = "Array";
    if(count($args) >= 2){
      $array->length = new Value($this->ecma, "Number", count($args));
      $array->Put("length", new Property(new Value($this->ecma, "Number", count($args))));
      for($i=0;$i<count($args);$i++)
        $array->Put($i, new Property($args[$i]));
    }elseif(count($args) == 1){
      if($args[0]->isNumber()){
        $array->length = $args[0];
        $array->Put("length", new Property($args[0]));
      }else{
        $array->length = new Value($this->ecma, "Number", 1);
        $array->Put("length", new Property(new Value($this->ecma, "Number", 1)));
        $array->Put("0", new Property($args[0]));
      }
    }else{
      $array->length = new Value($this->ecma, "Number", 0);
      $array->Put("length", new Property(new Value($this->ecma, "Number", 0)));
    }
    $array->Put("prototype", new Property(new Value($this->ecma, "Object", $this->proto), ["DontEnum", "DontDelete", "ReadOnly"]));
    return new Value($this->ecma, "Object", $array);
  }
}
