<?php
namespace Ecma;

use Types\Objects\GlobelObject\GlobelObject;
use Types\Objects\HeadObject\HeadObject;
use Parser\Parser;
use Stack\Stack;
use Types\Value\Value;
use Types\Reference\Reference;

class Ecma{
  private $object;
  private $scopes;

  public function __construct(){
    $this->scopes = new Stack();
    $this->enterScope(new Value("Object", new GlobelObject()));
  }

  public function getIdentify(string $name) : Reference{
    //this will look after a identify $name and return the value ;)
    for($i=$this->scopes->size()-1;$i>=0;$i--){
      if($this->scopes->get($i)[0]->value->HasProperty($name)){
        return new Reference($this->scopes->get($i)[0]->value->Get($name)->getValue(), $name, $this->scopes->get($i)[0]->value);
      }
    }

    throw new \RuntimeException("Unknown identify: ".$name);
  }

  public function GetValue($value){
    if(!($value instanceof Reference)){
      return $value;
    }

    return $value->GetValue();
  }

  public function getCurrentObject() : HeadObject{
    return $this->scopes->peek()[0]->value;
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

  private function enterScope(Value $binding){
    $this->scopes->push([
      $binding
    ]);
  }
}
