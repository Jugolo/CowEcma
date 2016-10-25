<?php
namespace Statment\EmptyStatment;

use Types\Completion\Completion;

class EmptyStatment implements \Statment\Statment\Statment{
  public function parse(\Ecma\Ecma $ecma) :  Completion{
     return new Completion(Completion::NORMAL);
  }
}
  