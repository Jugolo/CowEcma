<?php
namespace TestData\EchoTest;

use Types\Objects\HeadObject\HeadObject;
use Types\Objects\Call\Call;

class EchoTest extends HeadObject implements Call{
  public function Call($obj, array $args) : \Types\Value\Value{
    for($i=0;$i<count($args);$i++)
     echo $args[$i]->ToString()."<br>\r\n";
    return new \Types\Value\Value("Undefind", null);
  }
}
