<?php
namespace Statment\ForStatment;

use Statment\Statment\Statment;
use Types\Completion\Completion;

class ForStatment implements Statment{
  private $expresion;
  private $body;

  public function __construct(array $expresion, Statment $body){
    $this->expresion = $expresion;
    $this->body = $body;
  }

  public function parse(\Ecma\Ecma $ecma) : Completion{
    if(count($this->expresion) == 3)
     return $this->normalFor($ecma);
    return $this->forIn($ecma);
  }

  private function forIn(\Ecma\Ecma $ecma) : Completion{
    $expresion = $this->expresion[1]->parse($ecma)->GetValue()->ToObject();
    foreach($expresion->getPropertyName() as $name){
      $property = $expresion->Get($name);
      if(!$property->hasAttribute("DontEnum")){
        if($this->expresion[0] instanceof  \Expresion\IdentifyExpresion\IdentifyExpresion){
          $ecma->pushVariabel($this->expresion[0]->identify, new \Types\Value\Value("String", $name));
        }else{
          $this->expresion[0]->parse($ecma)->GetValue()->ToObject()->Put($name, $name);
        }
        $com = $this->body->parse($ecma);
        if(!$com->isNormal()){
           if($com->isReturn())
            return $com;
           if($com->isBreak())
            break;
           if($com->isContinue()){}else{
           echo "<pre>";print_r($com);exit("</pre>");
           }
        }
      }
    }
    return new Completion(Completion::NORMAL);
  }

  private function normalFor(\Ecma\Ecma $ecma) : Completion{
    if($this->expresion[0] !== null){
      $this->expresion[0]->parse($ecma);
    }

    while($this->expresion[1] == null ? true : $this->expresion[1]->parse($ecma)->GetValue()->ToBoolean()){
       $com = $this->body->parse($ecma);
       if(!$com->isNormal()){
          if($com->isReturn())
           return $com;
          if($com->isBreak())
           break;
          if($com->isContinue()){}else{
          echo "<pre>";print_r($com);exit("</pre>");
          }
       }
       if($this->expresion[2] != null)
         $this->expresion[2]->parse($ecma);
    }
    return new Completion(Completion::NORMAL);
  }
}
