<?php
namespace Ecma\Statment\BreakStatment;

use Ecma\Types\Completion\Completion;
use Ecma\Statment\Statment\Statment;
use Ecma\Ecma\Ecma;

class BreakStatment implements Statment{
  public function parse(Ecma $ecma) :  Completion{
     return new Completion(Completion::BREAKS);
  }
}
