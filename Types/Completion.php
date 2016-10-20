<?php
namespace Types\Completion;

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
}
