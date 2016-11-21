<?php
namespace TestData\EchoTest;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Value\Value;

class EchoTest extends HeadObject implements Call{
  public function Call($obj, array $args) : Value{
    for($i=0;$i<count($args);$i++)
     echo $args[$i]->ToString()."<br>\r\n";
    return new Value("Undefind", null);
  }
}
