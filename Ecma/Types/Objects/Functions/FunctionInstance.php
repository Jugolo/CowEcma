<?php
namespace Ecma\Types\Objects\Functions\FunctionInstance;

use Ecma\Types\Value\Value;
use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Constructor\Constructor;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Objects\Property\Property;
use Ecma\ExcuteContext\ExcuteContext;
use Ecma\Ecma\Ecma;

class FunctionInstance extends HeadObject implements Constructor, Call{
  protected $ecma;
  private $body;
  private $args;
  private $orgPrototype;
  public function __construct(Ecma $ecma, string $args, string $body, HeadObject $prototype){
    $this->Prototype = $prototype;
    $this->args = explode(", ", $args);
    $this->Put("length", new Property(new Value($ecma, "Number", count($this->args))));
    $this->Put("prototype", new Property(new Value($ecma, "Object", $this->Prototype)));
    $this->ecma = $ecma;
    $this->body = $body;
  }

  public function Construct(array $arg) : Value{
    $object = new HeadObject();
    if($this->HasProperty("prototype"))
     $object->Prototype = $this->Get("prototype")->value->ToObject();
    $result = $this->Call(new Value($this->ecma, "Object", $object), $arg);
    if($result->isObject())
      return $result;
    return new Value($this->ecma, "Object", $object);
  }

  public function Call(Value $obj, array $args) : Value{
     if(!$obj->isObject()){
       $obj = new Value($obj->ecma, "Object", $obj->globel);
     }
     $activation = new HeadObject();
     $agument = new HeadObject();
     $activation->Put("arguments", new Property(new Value($obj->ecma, "Object", $agument), ["DontEnum"]));
     $agument->Prototype = $this->Get("prototype")->value->ToObject();
     $agument->Put("callee", new Property(new Value($obj->ecma, "Object", $this), ["DontEnum"]));
     $agument->Put("length", new Property(new Value($obj->ecma, "Number", count($args)), ["DontEnum"]));

     for($i=0;$i<count($args) && $i<count($this->args);$i++){
       $agument->Put($this->args[$i], new Property($args[$i]));
       $agument->Put($i, new Property($args[$i]));
       $activation->Put($this->args[$i], new Property($args[$i]));
     }

     $this->ecma->enterScope(new ExcuteContext($activation, $activation, $obj->ToObject()));
     $return = $this->ecma->parse($this->body);
     $this->ecma->leaveScope();
     if($return->isReturn())
       return $return->GetValue();
     return new Value($obj->ecma, "Undefined", null);
  }
}
