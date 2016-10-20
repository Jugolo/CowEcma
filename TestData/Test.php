<?php
namespace TestData\Test;

use Types\Objects\HeadObject\HeadObject;
use Types\Objects\Call\Call;

class Test extends HeadObject implements Call{
  public function Call($obj, array $args){
    if($args[0] === null){
      echo "<pre>";
      print_r($args);
      exit("</pre>");
    }
    if(!\Compare\Compare::compare($args[0], $args[1])){
      echo "<pre>";
      print_r($args[0]);
      print_r($args[1]);
      echo ("</pre>");
      throw new \RuntimeException($args[2]->ToString());
    }
  }
}
