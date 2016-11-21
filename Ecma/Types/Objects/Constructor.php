<?php
namespace Ecma\Types\Objects\Constructor;

use Ecma\Types\Value\Value;

interface Constructor{
  function Construct(array $arg) : Value;
}
