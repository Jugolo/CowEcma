<?php
namespace Expresion\BaseExpresion;

interface BaseExpresion{
  function parse(\Ecma\Ecma $ecma) : \Expresion\ExpresionResult\ExpresionResult;
}
