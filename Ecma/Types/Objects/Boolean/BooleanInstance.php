<?php
namespace Ecma\Types\Objects\Boolean\BooleanInstance;

use Ecma\Ecma\Ecma;
use Ecma\Types\Value\Value;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Objects\HeadObject\HeadObject;

class BooleanInstance extends HeadObject{
  protected $ecma;
  public function __construct(Ecma $ecma, bool $bool){
    $this->Put("prototype", new Property(new Value($ecma, "Object", $ecma->_boolean)));
  }
}
