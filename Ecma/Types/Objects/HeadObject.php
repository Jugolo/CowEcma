<?php
namespace Ecma\Types\Objects\HeadObject;

use Ecma\Types\Value\Value;
use Ecma\Types\Objects\Property\Property;

interface HeadObjectDelete{
  function Delete(string $propertyname);
}

class HeadObject implements HeadObjectDelete{
  protected $propertys = [];

  public $Prototype;
  public $Class;
  public $Value;

  public function getPropertyName(){
    return array_keys($this->propertys);
  }

  public function Get(string $propertyname) : Property{
    if(!empty($this->propertys[$propertyname])){
      return $this->propertys[$propertyname];
    }
    if($this->Prototype === null){
      //this is hack. But is the smart way to do it ;) 
      if(empty($this->ecma)){
        throw new \RuntimeException("Unkown property ".$propertyname);
      }
      return new Property(new Value($this->ecma, "Undefined", null));
    }

    return $this->Prototype->Get($propertyname);
  }

  public function Put(string $propertyname, Property $value){
    if(!$this->CanPut($propertyname))
      return;

    if($this->HasProperty($propertyname)){
      $this->propertys[$propertyname] = $value;
    }
    $this->propertys[$propertyname] = $value;
  }

  public function CanPut($propertyname){
    if($this->HasProperty($propertyname) && $this->propertys[$propertyname]->hasAttribute("ReadOnly"))
        return false;

    if($this->Prototype === null)
      return true;

    if($this->Prototype instanceof HostObject)
      return false;

    return $this->Prototype->CanPut($propertyname);
  }

  public function HasProperty($propertyname){
     if(!empty($this->propertys[$propertyname]))
       return true;
     if($this->Prototype === null)
       return false;
     return $this->Prototype->HasProperty($propertyname);
  }

  public function Delete(string $propertyname){
    if(empty($this->propertys[$propertyname]))
      return true;
    if($this->propertys[$propertyname]->hasAttribute("DontDelete"))
      return false;
    unset($this->propertys[$propertyname]);
    return true;
  }

  public function DefaultValue(string $hint, Ecma $ecma){
    switch($hint){
      case "String":
        $obj = $this->Get("toString")->getValue();
        if($obj->isObject()){
          return $obj->ToObject()->Call(new Value($ecma, "Object", $this), [])->ToString();
        }
        $obj = $this->Get("valueOf")->getValue();
        if($obj->isObject()){
          exit("DefaultValue('String').valueOf() this");
        }
        throw new \RuntimeException("Could not resovle DefaultValue('String');");
      default:
        $obj = $this->Get("valueOf");
        if($obj->isObject()){
          exit("DefaultValue('Number').valueOf() this");
        }
        $obj = $this->Get("toString");
        if($obj->isObject()){
          exit("DefaultValue('Number').toString() this");
        }
        throw new RuntimeError("Could not resovle DefaultValue('Number');");
    }
  }
}
