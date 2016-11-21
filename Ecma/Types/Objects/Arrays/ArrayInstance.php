<?php
namespace Ecma\Types\Objects\Arrays\ArrayInstance;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Value\Value;

class ArrayInstance extends HeadObject{
  public $length;

  public function Put(string $propertyname, Property $value){
       if($propertyname == "length") {
        if($value->getValue()->ToNumber() < $this->length->ToNumber()) {
               foreach ($this->propertys as $index => $value) {
                   if (is_numeric($index) && $index >= $value->value) {
                       $this->Delete($index);
                   }
               }
           }
           $this->length = $value->getValue();
           return parent::Put("length", new Property($value->getValue()));
       } else {
           if (is_numeric($propertyname)) {
               if ($propertyname >= $this->length->ToNumber()) {
                   $this->length = new Value("Number", $propertyname+1);
               }
           }
           return parent::Put($propertyname, $value);
       }
  }
}
