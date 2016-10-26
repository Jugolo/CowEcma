<?php
namespace Types\Objects\HeadObject;

use Types\Value\Value;
use Types\Objects\Property\Property;

interface HeadObjectDelete{
  function Delete($propertyname);
}

class HeadObject implements HeadObjectDelete{
  protected $propertys = [];

  public $Prototype;
  public $Class;
  public $Value;

  public function getPropertyName(){
    return array_keys($this->propertys);
  }

  public function Get(string $propertyname){
    if(!empty($this->propertys[$propertyname])){
      return $this->propertys[$propertyname];
    }
    if($this->Prototype === null){
      return new Value("Undefined", null);
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

  public function Delete($propertyname){
    if(empty($this->propertys[$propertyname]))
      return true;
    if($this->propertys[$propertyname]->hasAttribute("DontDelete"))
      return false;
    unset($this->propertys[$propertyname]);
    return true;
  }

  public function DefaultValue(string $hint){
    switch($hint){
      case "String":
        $obj = $this->Get("toString");
        if($obj->isObject()){
          exit("DefaultValue('String').toString() this");
        }
        $obj = $this->Get("valueOf");
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
