<?php
namespace Types\Objects\GlobelObject\FunctionObject;

use Types\Objects\HeadObject\HeadObject;
use Types\Objects\Constructor\Constructor;
use Types\Objects\Property\Property;
use Types\Value\Value;

class FunctionObject extends HeadObject implements Constructor{
  public function __construct(){
    $this->Put("prototype", new Property(new Value("Object", new HeadObject()), [" DontEnum", "DontDelete", "ReadOnly"]));
    $this->Put("length", new Property(new Value("Number", 1)));
  }
  public function Construct(array $arg) : Value{
    $P = "";
    $body = "";
    if(count($arg) > 0){
      if(count($arg) == 1){
        $body = $arg[0]->ToString();
      }else{
        $P = $arg[0]->ToString();
        for($i=1;$i<count($arg)-1;$i++)
          $P .= $arg[$i]->ToString();
        $body = array_pop($arg);
      }
      $obj = new FunctionObjects();
      $obj->Class = "Function";
      $obj->prototype = $this->prototype;
      $obj->addCall(function($obj, $args) use($P, $body, $arg){

      });
      $obj->addConstruct(function($args) use($P, $body, $arg){

      });
    }
  }
}

class FunctionObjects extends HeadObject  implements Constructor{
  private $caller;
  private $constructor;

  public function Call($obj, $arg){
    if($this->caller !== null){
      return call_user_func_array($this->caller, [$obj, $arg]);
    }
  }

  public function addCall(callable $func){
    $this->caller = $func;
  }

  public function addConstruct(callable $func){
    $this->constructor = $func;
  }

  public function Construct(array $arg) : Value{
    if($this->constructor !== null){
      return call_user_func_array($this->constructor, [$arg]);
    }
  }
}
