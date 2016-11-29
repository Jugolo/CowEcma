<?php
namespace Ecma\Types\Objects\String\StringInstance;

use Ecma\Ecma\Ecma;
use Ecma\Types\Value\Value;
use Ecma\Types\Objects\HeadObject\HeadObject;

class StringInstance extends HeadObject{
  protected $ecma;
  
  public function __construct(string $str, Ecma $ecma){
    $this->Prototype = $ecma->str;
    $this->Value = $str;
  }
}
