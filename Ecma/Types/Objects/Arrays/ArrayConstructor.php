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
  protected $ecma;

  public function __construct(Ecma $ecma){
    $ecma->_array = new ArrayPrototype($this, $ecma);
    $this->ecma = $ecma;
  }
  public function Call(Value $obj, array $args) : Value{
    return $this->Construct($args);
  }

  public function Construct(array $args) : Value{
    return new Value($this->ecma, "Object", new ArrayInstance($this->ecma, $args));
  }
}
