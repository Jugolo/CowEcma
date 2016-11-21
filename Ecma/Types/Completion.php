<?php
namespace Ecma\Types\Completion;

use Ecma\Types\Value\Value;

class Completion{
  private $state;
  private $value;

  const NORMAL         = 1;
  const BREAKS         = 2;
  const CONTINUES      = 3;
  const RETURNS        = 4;

  public function __construct(int $state, $value = null){
    $this->value = $value;
    $this->state = $state;
  }

  public function isNormal(){
    return $this->state === self::NORMAL;
  }

  public function isReturn(){
    return $this->state == self::RETURNS;
  }

  public function isBreak(){
    return $this->state == self::BREAKS;
  }

  public function isContinue(){
    return $this->state == self::CONTINUES;
  }

  public function GetValue() : Value{
    if($this->value == null){
      return new Value("Undefined", null);
    }

    return $this->value->GetValue();
  }
}
