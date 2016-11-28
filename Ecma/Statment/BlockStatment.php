<?php
namespace Ecma\Statment\BlockStatment;

use Ecma\Types\Completion\Completion;
use Ecma\Statment\Statment\Statment;
use Ecma\Ecma\Ecma;

class BlockStatment implements Statment{
  private $statments = [];

  public function __construct(array $statments){
    $this->statments = $statments;
  }

  public function parse(Ecma $ecma) : Completion{
    foreach($this->statments as $statment){
      $c = $statment->parse($ecma);
      if(!$c->isNormal())
        return $c;
    }
    return new Completion($ecma, Completion::NORMAL);
  }
}
