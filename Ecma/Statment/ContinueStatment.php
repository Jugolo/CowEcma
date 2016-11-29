<?php
namespace Ecma\Statment\ContinueStatment;

use Ecma\Types\Completion\Completion;
use Ecma\Statment\Statment\Statment;
use Ecma\Ecma\Ecma;

class ContinueStatment implements Statment{
  public function parse(Ecma $ecma) :  Completion{
     return new Completion($ecma, Completion::CONTINUES);
  }
}
