<?php
namespace Ecma\Types\Objects\GlobelObject\Objects;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Ecma\Ecma;

class Objects extends HeadObject{
  private $ecma;
  public function __construct(Ecma $ecma){
    $this->prototype = new HeadObject();
    $this->ecma = $ecma;
  }

  public function Construct($arg){
    if(empty($arg) || $arg[0]->isNull() || $arg[0]->isUndefined()){
      $obj = new Objects();
      $obj->prototype = $this->prototype;
      $obj->Class = "Object";
      return new Value($this->ecma, "Object", $obj);
    }

    return $arg[0]->ToObject();
  }

  public function Call(array $arg){
    return $this->Construct($arg);
  }
}
