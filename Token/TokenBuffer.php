<?php
namespace Token\TokenBuffer;

class TokenBuffer{
  private $data = [];

  public function __construct(string $type, string $value, int $line){
    $this->data = [
      "type"  => $type,
      "value" => $value,
      "line"  => $line,
    ];
  }

  public function __get($name){
    if(empty($this->data[$name]))
      return null;

    return $this->data[$name];
  }
}
