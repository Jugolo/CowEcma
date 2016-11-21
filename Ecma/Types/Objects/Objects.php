<?php
namespace Ecma\Types\Objects\GlobelObject\Objects;

use Ecma\Types\Objects\HeadObject\HeadObject;

class Objects extends HeadObject{
  public function __construct(){
    $this->prototype = new HeadObject();
  }

  public function Construct($arg){
    if(empty($arg) || $arg[0]->isNull() || $arg[0]->isUndefined()){
      $obj = new Objects();
      $obj->prototype = $this->prototype;
      $obj->Class = "Object";
      return new Value("Object", $obj);
    }

    return $arg[0]->ToObject();
  }

  public function Call(array $arg){
    return $this->Construct($arg);
  }
}
