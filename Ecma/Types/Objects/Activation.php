<?php
namespace Ecma\Types\Objects\Activation;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\AgumentObject\AgumentObject;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Value\Value;

class Activation extends HeadObject{
  public function __construct(HeadObject $obj, array $definedArg, array $callArg){
   if($obj->HasProperty("arguments"))
    $argument = $obj->Get("arguments");
   else
    $argument = new AgumentObject($obj, $this, $definedArg, $callArg);

   $this->Put("arguments", new Property(new Value("Object", $argument), ["DontDelete"]));
  }
}
