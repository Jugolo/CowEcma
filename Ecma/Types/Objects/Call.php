<?php
namespace Ecma\Types\Objects\Call;

use Ecma\Types\Value\Value;

interface Call{
  function Call(Value $obj, array $args) : Value;
}
