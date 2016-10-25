<?php
namespace Statment\Statment;

interface Statment{
  function parse(\Ecma\Ecma $ecma) : \Types\Completion\Completion;
}