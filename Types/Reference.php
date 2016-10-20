<?php
namespace Types\Reference;

use Types\Value\Value;
use Types\Objects\Property\Property;
use Types\Objects\GlobelObject\GlobelObject;

class Reference{
  private $value;
  private $name;
  private $globelObject;

  public function __construct(Value $value, string $name, GlobelObject $object){
    $this->value = $value;
    $this->name = $name;
    $this->globelObject = $object;
  }

  public function GetBase(){
    return $this->globelObject;
  }

  public function GetPropertyName() : string{
    return $this->name;
  }

  public function GetValue(){
    $base = $this->GetBase();
    if(!$base){
      throw new \RuntimeException("Reference->GetBase() return null");
    }

    return $base->Get($this->GetPropertyName())->value;
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
