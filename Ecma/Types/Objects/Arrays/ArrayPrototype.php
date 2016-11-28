<?php
namespace Ecma\Types\Objects\Arrays\ArrayPrototype;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Value\Value;
use Ecma\Types\Objects\Property\Property;
use Ecma\Compare\Compare;
use Ecma\Types\Objects\Arrays\ArrayConstructor\ArrayConstructor;
use Ecma\Ecma\Ecma;

class ArrayPrototype extends HeadObject{
  protected $ecma;

  public function __construct(ArrayConstructor $constructor, Ecma $ecma){
    $this->ecma = $ecma;
    $this->Put("constructor", new Property(new Value($ecma, "Object", $constructor)));
    $this->Put("toString", new Property(new Value($ecma, "Object", new ArrayToString())));
    $this->Put("join", new Property(new Value($ecma, "Object", new ArrayJoin())));
    $this->Put("reverse", new Property(new Value($ecma, "Object", new ArrayReverse())));
    $this->Put("sort", new Property(new Value($ecma, "Object", new ArraySort())));
  }
}

class ArraySort implements Call{
  public function Call(Value $obj, array $args) : Value{
    $length = $obj->Get("length")->getValue()->ToNumber();
    if($length <= 1){
      return $obj;
    }

    if(count($args) == 0){
      $args[0] = new Value($obj->ecma, "Undefine", null);
    }

    $array = $this->getCleanArray($obj);
    $call = [$this, "helper"];
    usort($array, function(Value $x, Value $y) use($call, $args, $obj){
      return call_user_func($call, $x, $y, $args[0], $obj);//this is cool :D
    });

    for($i=0;$i<count($array);$i++){
      $obj->Put(strval($i), new Property($array[$i]));
    }

    return new Value($obj->ecma, "Object", $obj);
  }

  private function getCleanArray($obj) : array{
    $length = $obj->Get("length")->getValue()->ToNumber();
    $return = [];
    for($i=0;$i<$length;$i++){
      $return[] = $obj->Get(strval($i))->getValue();
    }

    return $return;
  }

  public function helper(Value $x, Value $y, Value $comparefn, HeadObject $obj){
    if($x->isUndefined() || $y->isUndefined()){
      if($x->isUndefined() && $y->isUndefined()){
        return 0;
      }elseif($x->isUndefined()){
        return 1;
      }else{
        return -1;
      }
    }

    if($comparefn->isObject() && $comparefn instanceof Call){
      return $comparefn->ToObject()->Call($obj, [$x, $y])->ToNumber();
    }


    return 0;
  }
}

class ArrayJoin implements Call{
  public function Call(Value $obj, array $args) : Value{
    $length = $obj->Get("length")->getValue()->ToNumber();
    if($length == 0){
      return new Value($obj->ecma, "String", "");
    }
    if(count($args) == 0){
      $sep = ",";
    }else{
      $sep = $args[0]->ToString();
    }
    $str = [];
    for($i=0;$i<$length;$i++){
      $val = $obj->Get($i)->getValue();
      if($val->isNull() || $val->isUndefined()){
        $str[] = "";
      }else{
        $str[] = $val->ToString();
      }
    }

    return new Value($obj->ecma, "String", implode($sep, $str));
  }
}

class ArrayReverse implements Call{
  public function Call(Value $obj, array $args) : Value{
    $len = $obj->Get("length")->getValue()->ToNumber();
    $mid = floor($len / 2);
    $k = 0;
    while ($k != $mid) {
      $l = $len - $k - 1;
      if (!$obj->HasProperty($k)) {
        if (!$obj->HasProperty($l)) {
            $obj->Delete($k);
            $obj->Delete($l);
        } else {
            $obj->Put($k, new Property($obj->Get($l)->getValue()));
            $obj->Delete($l);
        }
    }else{
      if(!$obj->HasProperty($l)){
        $obj->Put($l, $obj->Get($k)->getValue());
        $obj->Delete($k);
        }else{
          $a = $obj->Get($k);
          $obj->Put($k, $obj->Get($l));
          $obj->Put($l, $a);
        }
      }
      $k++;
    }
    return new Value($obj->ecma, "Object", $obj);
  }
}

class ArrayToString implements Call{
  public function Call(Value $obj, array $args) : Value{
    return $obj->ToObject()->Get("join")->getValue()->ToObject()->Call($obj, []);
  }
}
