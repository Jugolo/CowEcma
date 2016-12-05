<?php
namespace Ecma\Types\Objects\Number\NumberInstance;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Property\Property;
use Ecma\Ecma\Ecma;

class NumberInstance extends HeadObject{
  protected $ecma;
  
  public function __construct(Ecma $ecma, int $number){
    $this->ecma = $ecma;
    $this->Value = $number;
    $this->Put("prototype", new Property($ecma->number));
  }
}
