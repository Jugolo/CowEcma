<?php
namespace Ecma\Types\Objects\Arrays\ArrayInstance;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Value\Value;
use Ecma\Ecma\Ecma;

class ArrayInstance extends HeadObject{
  public $length;
  protected $ecma;
  
  public function __construct(Ecma $ecma, array $args){
    $this->ecma = $ecma;
    $this->Prototype = $ecma->_array;
    $this->Class = "Array";
    $this->Put("prototype", new Property(new Value($ecma, "Object", $ecma->_array)));
    if(count($args) >= 2){
      $this->length = new Value($ecma, "Number", 0);
      for($i=0;$i<count($args);$i++){
        $this->Put(strval($i), new Property($args[$i]));
      }
    }elseif(count($args) == 1){
      if($args[0]->isNumber()){
        $this->Put("length", new Property($args[0]));
      }else{
        parent::Put("0", new Property($args[0]));
        parent::Put("length", new Property(new Value($ecma, "Number", 1)));
        $this->length = new Value($ecma, "Number", 1);
      }
    }else{
      $this->length = new Value($this->ecma, "Number", 0);
      $this->Put("length", new Property(new Value($this->ecma, "Number", 0)));
    }
  }

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
                   $this->length = new Value($this->ecma, "Number", $propertyname+1);
                   parent::Put("length", new Property($this->length));
               }
           }
           return parent::Put($propertyname, $value);
       }
  }
}
