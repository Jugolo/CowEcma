<?php
namespace Types\Reference;

use Types\Value\Value;
use Types\Objects\Property\Property;
use Types\Objects\HeadObject\HeadObject;

class Reference{
  private $value;
  private $name;

  public function __construct(HeadObject $value, string $name){
    $this->value = $value;
    $this->name = $name;
  }

  public function GetBase(){
    return $this->value;
  }

  public function GetPropertyName() : string{
    return $this->name;
  }

  public function GetValue() : Value{
    $base = $this->GetBase();
    if(!$base){
      throw new \RuntimeException("Reference->GetBase() return null");
    }

    $value = $base->Get($this->GetPropertyName());
    if($value instanceof Property){
      $value = $value->getValue();
    }
    if($value == null){
     echo "<pre>";print_r($this);exit("</pre>");
    }
    return $value;
  }

  public function PutValue(Value $w){
    $base = $this->GetBase();
    if($base != null){
      $base->Put($this->GetPropertyName(), new Property($w));
      return;
    }

    $this->globelObject->Put($this->GetPropertyName(), new Property($w));
  }
}
