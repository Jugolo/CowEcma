<?php
namespace TestData\TestPrint;

use Ecma\Types\Objects\Call\Call;
use Ecma\Types\Value\Value;

class TestPrint implements Call{
  public function Call($obj, array $arg) : Value{
    echo "<pre>";
    $this->render($arg[0], 0);
    exit("</pre>");
  }

  private function render(Value $value, int $number, string $prefix = ""){
    echo "\r\n".str_repeat(" ", $number).$prefix;
    switch($value->type){
      case "Object":
        echo "(Object){";
        foreach($value->value->getPropertyName() as $name){
          $this->render($value->value->Get($name)->GetValue(), $number+3, "'".$name."' : ");
        }
        echo "\r\n".str_repeat(" ", $number)."}";
      break;
      case "Number":
        echo "(Number)".$value->ToString();
      break;
      case "Undefined":
        echo "(Undefined)undefined";
      break;
      default:
        echo "(String)".$value->ToString();
    }
  }
}
