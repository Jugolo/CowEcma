<?php
namespace Ecma\Token\TokenBuffer;

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
    if(!array_key_exists($name, $this->data))
      return null;

    return $this->data[$name];
  }
}
