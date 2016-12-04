<?php
namespace Ecma\Types\Objects\Number\NumberPrototype;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Ecma\Ecma;

class NumberPrototype extends HeadObject{
  protected $ecma;
  
  public function __construct(Ecma $ecma){
    $this->ecma = $ecma
  }
}
