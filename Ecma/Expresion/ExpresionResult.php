<?php
namespace Ecma\Expresion\ExpresionResult;

use Ecma\Types\Value\Value;
use Ecma\Types\Reference\Reference;

class ExpresionResult{
  private $context;

  public function __construct($value){
    $this->context = $value;
  }

  public function GetValue() : Value{
    if($this->context instanceof Reference)
      return $this->context->GetValue();
    return $this->context;
  }

  public function GetBase(){
    return $this->context;
  }
}
