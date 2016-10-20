<?php
namespace TestData\TestType;

use Types\Objects\HeadObject\HeadObject;
use Types\Objects\Call\Call;

class Test extends HeadObject implements Call{
  public function Call($obj, array $args){
    $arg = [];
    for($i=0;$i<count($args);$i++){
      $arg[] = $args[$i]->value."(".$args[$i]->type.")";
    }
    exit("Test type(".count($args)."): ".implode(", ", $arg));
  }
}
