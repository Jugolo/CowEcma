<?php
namespace Ecma\Stack;

class Stack{
  private $_stack = [];

  public function size() : int{
    return count($this->_stack);
  }

  public function get(int $i){
    return $this->_stack[$i];
  }

  public function pop(){
    return array_pop($this->_stack);
  }

  public function peek(){
    return $this->_stack[count($this->_stack)-1];
  }

  public function push($context){
    $this->_stack[] = $context;
  }
}
