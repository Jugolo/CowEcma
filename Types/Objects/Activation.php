<?php
namespace Types\Objects\Activation;

class Activation extends \Types\Objects\HeadObject\HeadObject{
  public function __construct(\Types\Objects\HeadObject\HeadObject $obj, array $definedArg, array $callArg){
   if($obj->HasProperty("arguments"))
    $argument = $obj->Get("arguments");
   else
    $argument = new \Types\Objects\AgumentObject\AgumentObject($obj, $this, $definedArg, $callArg);

   $this->Put("arguments", new \Types\Objects\Property\Property(new \Types\Value\Value("Object", $argument), ["DontDelete"]));
  }
}