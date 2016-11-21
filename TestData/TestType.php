<?php
namespace TestData\TestType;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Value\Value;

class Test extends HeadObject implements Call{
  public function Call($obj, array $args) : Value{
    $arg = [];
    for($i=0;$i<count($args);$i++){
      $arg[] = $args[$i]->value."(".$args[$i]->type.")";
    }
    exit("Test type(".count($args)."): ".implode(", ", $arg));
  }
}
