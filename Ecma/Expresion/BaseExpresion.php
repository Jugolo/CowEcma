<?php
namespace Ecma\Expresion\BaseExpresion;

use Ecma\Ecma\Ecma;
use Ecma\Expresion\ExpresionResult\ExpresionResult;

interface BaseExpresion{
  function parse(Ecma $ecma) : ExpresionResult;
}
