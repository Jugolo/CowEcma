<?php
namespace Statment\BlockStatment;

use Types\Completion\Completion;

class BlockStatment implements \Statment\Statment\Statment{
  private $statments = [];

  public function __construct(array $statments){
    $this->statments = $statments;
  }

  public function parse(\Ecma\Ecma $ecma) : Completion{
    foreach($this->statments as $statment){
      $c = $statment->parse($ecma);
      if(!$c->isNormal())
        return $c;
    }
    return new Completion(Completion::NORMAL);
  }
}