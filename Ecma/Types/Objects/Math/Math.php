<?php
namespace Ecma\Types\Objects\Math\Math;

use Ecma\Ecma\Ecma;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Value\Value;

class Math extends HeadObject{
  protected $ecma;
  
  public function __construct(Ecma $ecma){
    $this->ecma = $ecma;
  }
}
