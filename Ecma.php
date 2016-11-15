<?php
namespace Ecma;

use Types\Objects\GlobelObject\GlobelObject;
use Types\Objects\HeadObject\HeadObject;
use Types\Objects\Property\Property;
use Parser\Parser;
use Stack\Stack;
use Types\Value\Value;
use Types\Reference\Reference;
use ExcuteContext\ExcuteContext;

class Ecma{
  public $object;
  public $globel;
  private $context;
  private $test = "This is to test CowEcma";

  public function __construct(){
    $this->context = new Stack();
    $this->globel = new GlobelObject($this);
    $this->enterScope(new ExcuteContext($this->globel, $this->globel, $this->globel));
  }

  public function getIdentify(string $name) : Reference{
    //this will look after a identify $name and return the value ;)
    for($i=$this->context->size()-1;$i>=0;$i--){
      if($this->context->get($i)->scope->HasProperty($name))
        return new Reference($this->context->get($i)->scope, $name);
    }

    throw new \RuntimeException("Unknown identify: ".$name);
  }

  public function getThis() : HeadObject{
    return $this->context->peek()->_this;
  }

  public function pushVariabel(string $name, Value $value){
    $this->context->peek()->scope->Put($name, new Property($value));
  }

  /**
  * Parse the code given in agument 1.
  *@arg $code string The code (or file if @arg 2 is true)
  *@arg $isFile boolean tell if the @arg 1 is a ecma code or dir to the file!
  *@return boolean true on success or false on fail
  */
  public function parse(string $code, bool $isFile = false){
    if($isFile){
       if(!file_exists($code)){
         return false;
       }
       $code = file_get_contents($code);
    }

    $parser = new Parser($code);
    return $parser->parse($this);
  }

  public function enterScope(ExcuteContext $context){
    $this->context->push($context);
  }

  public function leaveScope(){
    $this->context->pop();
  }
}
