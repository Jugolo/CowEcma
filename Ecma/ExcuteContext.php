<?php
namespace Ecma\ExcuteContext;

use Ecma\Types\Objects\HeadObject\HeadObject;

class ExcuteContext{
  private $data = [];
  public function __construct(HeadObject $scope, HeadObject $variabel, HeadObject $_this){
    $this->data = ["scope" => $scope, "variabel" => $variabel, "_this" => $_this];
  }

  public function __get($name){
    return $this->data[$name];
  }
}
