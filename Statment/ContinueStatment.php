<?php
namespace Statment\ContinueStatment;

use Types\Completion\Completion;

class ContinueStatment implements \Statment\Statment\Statment{
  public function parse(\Ecma\Ecma $ecma) :  Completion{
     return new Completion(Completion::CONTINUES);
  }
}
