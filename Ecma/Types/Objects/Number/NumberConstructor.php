<?php
namespace Ecma\Types\Objects\Number\NumberConstructor;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Ecma\Ecma;

class NumberConstructor extends HeadObject{
  protected $ecma;
  
  public function __construct(Ecma $ecma){
    $this->ecma = $ecma;
  }
}
