<?php
namespace Types\Objects\AgumentObject;

use Types\Objects\Property\Property;
use Types\Value\Value;
use Types\Objects\HeadObject\HeadObject;
use Types\Objects\Activation\Activation;

class AgumentObject extends HeadObject{
   public function __construct(HeadObject $obj, Activation $act, array $definedArg, array $calledArg){
     $this->Put("length", new Property(new Value("Number", count($calledArg)), ["DontEnum"]));
     $this->Put("callee", new Property(new Value("Object", $obj), ["DontEnum"]));
     for($i=0;$i<count($definedArg) && $i<count($calledArg);$i++){
       $this->Put($definedArg[$i], new Property($calledArg[$i]));
       $act->Put($definedArg[$i], new Property($calledArg[$i]));
       $this->Put($i, new Property($calledArg[$i]));
     }
     for(;$i<count($calledArg);$i++)
       $this->Put($i, new Property($calledArg[$i]));
   }
}