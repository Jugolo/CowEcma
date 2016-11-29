<?php
namespace Ecma\Types\Objects\GlobelObject\Functions;

use Ecma\Types\Objects\HeadObject\HeadObject;
use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Value\Value;
use Ecma\Ecma\Ecma;
use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Objects\GlobelObject\GlobelObject;
use Ecma\Math\Math;

class Functions{
  public function __construct(GlobelObject $container, Ecma $ecma){
    $container->Put("eval", new Property(new Value($ecma, "Object", new EvalFunction($ecma))));
    $container->Put("parseInt", new Property(new Value($ecma, "Object", new ParseIntFunction())));
    $container->Put("parseFloat", new Property(new Value($ecma, "Object", new ParseFloatFunction())));
    $container->Put("escape", new Property(new Value($ecma, "Object", new ParseEscapeFunction())));
    $container->Put("unescape", new Property(new Value($ecma, "Object", new ParseUnescapeFunction())));
    $container->Put("isNaN", new Property(new Value($ecma, "Object", new ParseIsNaNFunction())));
    $container->Put("isFinite", new Property(new Value($ecma, "Object", new ParseIsFiniteFunction())));
  }
}

class ParseIsFiniteFunction extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    return new Value($obj->ecma, "Boolean", is_finite($arg[0]->ToNumber()));
  }
}

class ParseIsNaNFunction extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    return new Value($ecma, "Boolean", is_nan($arg[0]->ToNumber()));
  }
}

class ParseUnescapeFunction extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $str = preg_split('//u', $arg[0]->ToString(), -1, PREG_SPLIT_NO_EMPTY);
    if($str == ""){
      return new Value($obj->ecma, "String", "");
    }
    $s = "";
    for($k=0;$k<count($str);$k++){
      if($str[$k] == "%"){
        if($str[$k+1] == "u"){
          if($this->isHex($str[$k+2]) && $this->isHex($str[$k+3]) && $this->isHex($str[$k+4]) && $this->isHex($str[$k+5])){
            $s .= Math::unichr(hexdec($str[$k+2].$str[$k+3].$str[$k+4].$str[$k+5]));
            $k += 5;
            continue;
          }
        }else{
          if($this->isHex($str[$k+1]) && $this->isHex($str[$k+2])){
            $s .= Math::unichr(hexdec($str[$k+1].$str[$k+2]));
            $k += 2;
            continue;
          }else{
            $s .= $str[$k];
          }
        }
      }else{
        $s .= $str[$k];
      }
    }

    return new Value($obj->ecma, "String", $s);
  }

  private function isHex(string $char){
    return ($ansii = ord($char)) >= 65 && $ansii <= 70 || $ansii >= 97 && $ansii <= 102 || $ansii >= 48 && $ansii <= 57;
  }
}

class ParseEscapeFunction extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $str = preg_split('//u', $arg[0]->ToString(), -1, PREG_SPLIT_NO_EMPTY);
    $R = "";
    if(count($str) == 0){
      return new Value($obj->value, "String", "");
    }

    for($k=0;$k<count($str);$k++){
       if(strpos("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789 @*_+-./,", $str[$k]) === false){
         $ansii = Math::ordutf8l($str[$k], 0);
         if($ansii > 256){
           $hex = dechex($ansii);
           if(strlen($hex) != 4){
             $S = "%u".str_repeat("0", 4-strlen($hex)).$hex;
           }else{
             $S = "%u".$hex;
           }
         }else{
           $S = "%".strtoupper(dechex($ansii));
         }
       }else{
         $S = $str[$k];
       }

       $R .= $S;
    }

    return new Value($obj->ecma, "String", $R);
  }
}

class EvalFunction extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    if(!$arg[0]->isString())
      return $arg[0];

    return $obj->ecma->parse($arg[0]->ToString())->GetValue();
  }
}

class ParseFloatFunction extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $str = ltrim($arg[0]->ToString());
    $ansii = ord($str[0]);
    if($ansii < 48 && $ansii > 57){
      return new Value($obj->ecma, "Number", NAN);
    }

    $int = "";
    for($i=0;$i<strlen($str);$i++){
      $ansii = ord($str[$i]);
      if($ansii >= 48 && $ansii <= 57){
        $int .= $str[$i];
      }else{
        break;
      }
    }

    return new Value($obj->ecma, "Number", intval($int));
  }
}

class ParseIntFunction extends HeadObject implements Call{
  public function Call(Value $obj, array $arg) : Value{
    $str = ltrim($arg[0]->ToString());
    $sign = 1;

    if(strlen($str) != 0){
      if($str[0] == "-"){
        $sign = -1;
      }

      if($str[0] == "-" || $str[0] == "+"){
        $str = substr($str, 1);
      }

      if(count($arg) >= 2){
        $redix = $arg[1]->ToNumber();
        if($redix != 0){
          if($redix < 2 || $redix > 36){
            return new Value($obj->ecma, "Number", NAN);
          }
          if($redix == 16 && strlen($str) >= 2){
            $prefix = substr($str, 0, 2);
            if($prefix == "0x" || $prefix == "0X"){
              return $this->step22(substr($str, 2), $sign, $obj->ecma);
            }
            return $this->step22($str, 0, $sign, $obj->ecma);
          }
        }
      }

      if(!empty($str) && $str[0] == 0){
        if(strlen($str) >= 2 && ($str[1] != "x" || $str[1] != "X")){
          return $this->step22($str, 8, $sign, $obj->ecma);
        }
        return $this->step22(substr($str, 2), 16, $sign, $obj->ecma);
      }
    }

    return $this->step22($str, 10, $sign, $obj->ecma);
  }

  private function step22(string $s, int $R, int $sign, Ecma $ecma) : Value{
    if(strlen($s) == 0){
      return new Value($ecma, "Number", NAN);
    }

    $result = 0;
    $pow = 1;
    for($i = strlen($s)-1;$i>=0;$i--){
      $index = NAN;
      $digit = ord($s[$i]);

      if($digit >= 48 && $digit <= 57){
          $index = $digit - 48;
      }elseif($digit >= 97 && $digit <= 122){
          $index = $digit - 107;
      }elseif($digit >= 65 && $digit <= 90){
          $index = $digit - 75;
      }

      if($index == NAN || $index >= $R){
            return $this->step22(substr($s, 0, $i), $R, $ecma);
      }

      $result += $index*$pow;
      $pow = $pow * $R;
    }

    return new Value($ecma, "Number", $sign * $result);
  }
}
