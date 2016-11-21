<?php
namespace Ecma\Statment\Statment;

use Ecma\Ecma\Ecma;
use Ecma\Types\Completion\Completion;

interface Statment{
  function parse(Ecma $ecma) : Completion;
}
