<?php
namespace Expresion\ExpresionResult;

class ExpresionResult{
  private $context;

  public function __construct($value){
    $this->context = $value;
  }

  public function GetValue() : \Types\Value\Value{
    if($this->context instanceof \Types\Reference\Reference)
      return $this->context->GetValue();
    return $this->context;
  }
 
  public function GetBase(){
    return $this->context;
  }
}