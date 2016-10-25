<?php
namespace Types\Objects\Call;

interface Call{
  function Call($obj, array $args) : \Types\Value\Value;
}
