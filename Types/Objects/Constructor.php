<?php
namespace Types\Objects\Constructor;

use Types\Value\Value;

interface Constructor{
  function Construct(array $arg) : Value;
}