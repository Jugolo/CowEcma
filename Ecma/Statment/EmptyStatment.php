<?php
namespace Ecma\Statment\EmptyStatment;

use Ecma\Types\Completion\Completion;
use Ecma\Statment\Statment\Statment;
use Ecma\Ecma\Ecma;

class EmptyStatment implements Statment{
  public function parse(Ecma $ecma) :  Completion{
     return new Completion(Completion::NORMAL);
  }
}
