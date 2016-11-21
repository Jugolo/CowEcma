<?php
namespace Ecma\Types\Objects\Call;

use Ecma\Types\Value\Value;

interface Call{
  function Call($obj, array $args) : Value;
}
