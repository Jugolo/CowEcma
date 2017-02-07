<?php
namespace Ecma\Types\Objects\Functions\FunctionConstructor;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Constructor\Constructor;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Value\Value;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Objects\Functions\FunctionPrototype\FunctionPrototype;
use Ecma\Types\Objects\Functions\FunctionInstance\FunctionInstance;
use Ecma\Ecma\Ecma;

class FunctionConstructor extends HeadObject implements Constructor, Call{

 protected $ecma;

  public function __construct(Ecma $ecma){
    $this->ecma = $ecma;
    $this->ecma->_function = new FunctionPrototype($this, $ecma);
    $this->Put("prototype", new Property(new Value($ecma, "Object", $this->ecma->_function), ["DontEnum", "DontDelete", "ReadOnly"]));
    $this->Put("length", new Property(new Value($ecma, "Number", 1)));
  }

  public function Call(Value $obj, array $arg) : Value{
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
    return new Value($this->ecma, "Object", $func);
  }
}
