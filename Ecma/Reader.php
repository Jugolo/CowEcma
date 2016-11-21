<?php
namespace Ecma\Reader;

class Reader{

  const LF_LINETERMINATOR = 10;
  const CR_LINETERMINATOR = 13;

  const TAB_WHITESPACE = 9;
  const VT_WHITESPACE  = 11;
  const FF_WHITESPACE  = 12;
  const SP_WHITESPACE  = 32;

  private $peek = null;
  public $index = -1;
  private $code;

  public function __construct(string $code){
    $this->code = $code;
  }

  public function peek(){
    if($this->peek != null)
      return $this->peek;

    return $this->get($this->index+1);
  }

  public function current(){
    if($this->index == -1){
      return $this->next();
    }

    return $this->eof() ? null : $this->code[$this->index];
  }

  public function next(){
    $this->index++;
    return $this->eof() ? null : $this->code[$this->index];
  }

  public function pop(){
    if($this->index == -1){
      $this->index++;
    }

    $buffer = $this->eof() ? null : $this->code[$this->index];
    $this->index++;
    return $buffer;
  }

  public function eof(){
    return strlen($this->code)-1 < $this->index;
  }

  public function isLineTerminator(){
    $ansii = ord($this->current());
    return $ansii == self::LF_LINETERMINATOR || $ansii == self::CR_LINETERMINATOR;
  }

  public function isWhiteSpace(){
    $ansii = ord($this->current());
    return $ansii == self::TAB_WHITESPACE || $ansii == self::VT_WHITESPACE || $ansii == self::FF_WHITESPACE || $ansii == self::SP_WHITESPACE;
  }

  private function get(int $index){
    if(strlen($this->code)-1 < $index){
       return null;
    }

    return $this->code[$index];
  }
}
