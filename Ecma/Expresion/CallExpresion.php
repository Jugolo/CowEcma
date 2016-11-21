<?php
namespace Ecma\Expresion\CallExpresion;

use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Expresion\ExpresionResult\ExpresionResult;
use Ecma\Ecma\Ecma;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Reference\Reference;
use Ecma\Types\Objects\Activation\Activation;

class CallExpresion implements BaseExpresion{
  private $func;
  private $args;

  public function __construct(BaseExpresion $expresion, array $arguments){
    $this->func = $expresion;
    $this->args = $arguments;
  }

  public function parse(Ecma $ecma) : ExpresionResult{
    $base = $this->func->parse($ecma);
    $arg = [];
    for($i=0;$i<count($this->args);$i++)
      $arg[] = $this->args[$i]->parse($ecma)->GetValue();
    $value = $base->GetValue();
    if(!$value->isObject())
      throw new \RuntimeException("Cant not call a non function");
    $value = $value->ToObject();
    if(!($value instanceof Call)){
      throw new \RuntimeException("The object don't implements Call");
    }

    if($base->GetBase() instanceof Reference){
       $b = $base->GetBase()->GetBase();
       if($b instanceof Activation){
         $b = null;
       }
    }else
       $b = null;

    return new ExpresionResult($value->Call($b, $arg));
  }
}
