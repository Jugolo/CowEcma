<?php
namespace Ecma\Types\Objects\String\StringConstructor;

use Ecma\Ecma\Ecma;
use Ecma\Types\Objects\HeadObject\HeadObject;

class StringConstructor extends HeadObject{
  public function __construct(Ecma $ecma){
    $ecma->str = new StringPrototype($ecma);
  }
}
