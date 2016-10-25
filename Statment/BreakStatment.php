<?php
namespace Statment\BreakStatment;

use Types\Completion\Completion;

class BreakStatment implements \Statment\Statment\Statment{
  public function parse(\Ecma\Ecma $ecma) :  Completion{
     return new Completion(Completion::BREAKS);
  }
}
