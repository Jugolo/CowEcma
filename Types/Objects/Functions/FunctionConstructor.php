<?php
namespace Types\Objects\Functions\FunctionConstructor;

use Types\Objects\HeadObject\HeadObject;
use Types\Objects\Constructor\Constructor;
use Types\Objects\Call\Call;
use Types\Value\Value;
use Types\Objects\Property\Property;
use Types\Objects\Functions\FunctionPrototype\FunctionPrototype;
use Types\Objects\Functions\FunctionInstance\FunctionInstance;

class FunctionConstructor extends HeadObject implements Constructor, Call{

 private $ecma;

  public function __construct(\Ecma\Ecma $ecma){
    $this->Put("prototype", new Property(new Value("Object", new FunctionPrototype($this)), ["DontEnum", "DontDelete", "ReadOnly"]));
    $this->Put("length", new Property(new Value("Number", 1)));
    $this->ecma = $ecma;
  }

  public function Call($obj, array $arg) : Value{
    return $this->Construct($arg);
  }

  public function Construct(array $arg) : Value{
    if(count($arg) != 0){
      if(count($arg) == 1){
        $body = $arg[0]->value;
        $args = "";
      }else{
        $args = implode(", ", array_slice(array_map(function($a){return $a->value;}, $arg), 0, count($arg)-1));
        $body = $arg[count($arg)-1]->value;
      }
    }else{
      $body = "";
      $args = "";
    }
    $func =  new FunctionInstance($this->ecma, $args, $body, $this->Get("prototype")->GetValue()->value);
    $func->Class = "Function";
    return new Value("Object", $func);
  }
}
