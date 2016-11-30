<?php
namespace Ecma\Types\Objects\Boolean\BooleanConstructor;

use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Objects\Constructor;
use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Ecma\Ecma;

class BooleanConstructor extends HeadObject implements Call, Constructor{
  protected $ecma;
  
  public function __construct(Ecma $ecma){
    $this->ecma = $ecma;
  }
}
