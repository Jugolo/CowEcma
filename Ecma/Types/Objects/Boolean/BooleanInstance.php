<?php
namespace Ecma\Types\Objects\Boolean\BooleanInstance;

class BooleanInstance extends HeadObject{
  protected $ecma;
  public function __construct(Ecma $ecma, bool $bool){
    $this->Put("prototype", new Property(new Value($ecma, "Object", $ecma->_boolean)));
  }
}
