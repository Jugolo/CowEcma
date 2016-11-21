<?php
namespace TestData\Test;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Call\Call;
use Ecma\Compare\Compare;
use Ecma\Types\Value\Value;

class Test extends HeadObject implements Call{
  public function Call($obj, array $args) : Value{
    if(!Compare::compare($args[0], $args[1])){
      echo "<pre>";
      print_r($args[0]);
      print_r($args[1]);
      echo ("</pre>");
      throw new \RuntimeException($args[2]->ToString());
    }
    return new Value("Undefined", null);
  }
}
