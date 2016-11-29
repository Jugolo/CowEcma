<?php
namespace Ecma\Types\Objects\String\StringConstructor;

use Ecma\Ecma\Ecma;

class StringConstructor extends HeadObject{
  public function __construct(Ecma $ecma){
    $ecma->str = new StringPrototype();
  }
}
