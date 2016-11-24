<?php
namespace Ecma\Token\TokenCreater;

use Ecma\Reader\Reader;
use Ecma\Token\TokenBuffer\TokenBuffer;
use Ecma\StringBuilder\StringBuilder;

class TokenCreater{
  private $reader;
  private $current = null;
  private $line = 1;

  public function __construct(Reader $reader){
    $this->reader = $reader;
  }

  public function currentToken(){
    if($this->current == null){
      return $this->next();
    }

    return $this->current;
  }

  public function next(){
    return $this->current = $this->makeToken();
  }

  private function makeToken(){
    $this->skipJunk();
    if($this->reader->eof()){
      return new TokenBuffer("EOF", "End of file", $this->line);
    }
    if($this->isIdentifyStart()){
      return $this->getIdentify();
    }
    if($this->isDigit()){
      return $this->getDigit();
    }

    if($this->reader->current() == "'" || $this->reader->current() == '"'){
      return $this->getString();
    }

    return $this->getPunctuator();
  }

  private function getPunctuator(){

    switch($this->reader->current()){
      case '?':
        $this->reader->pop();
        return new TokenBuffer("punctuator", "?", $this->line);
      case ';':
        $this->reader->pop();
        return new TokenBuffer("punctuator", ";", $this->line);
      case '[':
        $this->reader->pop();
        return new TokenBuffer("punctuator", "[", $this->line);
      case ']':
        $this->reader->pop();
        return new TokenBuffer("punctuator", "]", $this->line);
      case '(':
        $this->reader->pop();
        return new TokenBuffer("punctuator", "(", $this->line);
      case ')':
        $this->reader->pop();
        return new TokenBuffer("punctuator", ")", $this->line);
      case '{':
        $this->reader->pop();
        return new TokenBuffer("punctuator", "{", $this->line);
      case '}':
        $this->reader->pop();
        return new TokenBuffer("punctuator", "}", $this->line);
      case '/':
        $this->reader->pop();
        if($this->reader->current() == "="){
          $this->reader->pop();
          return new TokenBuffer("punctuator", "/=", $this->line);
        }
        return new TokenBuffer("punctuator", "/", $this->line);
      case '*':
        $this->reader->pop();
        if($this->reader->current() == "="){
          $this->reader->pop();
          return new TokenBuffer("punctuator", "*=", $this->line);
        }
        return new TokenBuffer("punctuator", "*", $this->line);
      case '%':
        $this->reader->pop();
        if($this->reader->current() == "="){
          $this->reader->pop();
          return new TokenBuffer("punctuator", "%=", $this->line);
        }
        return new TokenBuffer("punctuator", "%", $this->line);
      case '.':
        $this->reader->pop();
        return new TokenBuffer("punctuator", ".", $this->line);
      case '=':
        $this->reader->pop();
        if($this->reader->current() == "="){
          $this->reader->pop();
          return new TokenBuffer("punctuator", "==", $this->line);
        }
        return new TokenBuffer("punctuator", "=", $this->line);
      case '-':
        $this->reader->pop();
        if($this->reader->current() == "="){
          $this->reader->pop();
          return new TokenBuffer("punctuator", "-=", $this->line);
        }elseif($this->reader->current() == "-"){
          $this->reader->pop();
          return new TokenBuffer("punctuator", "--", $this->line);
        }
        return new TokenBuffer("punctuator", "-", $this->line);
      case '+':
        $this->reader->pop();
        if($this->reader->current() == "="){
          $this->reader->pop();
          return new TokenBuffer("punctuator", "+=", $this->line);
        }elseif($this->reader->current() == "+"){
          $this->reader->pop();
          return new TokenBuffer("punctuator", "++", $this->line);
        }
        return new TokenBuffer("punctuator", "+", $this->line);
      case '<':
        $this->reader->pop();
        if($this->reader->current() == "="){
          $this->reader->pop();
          return new TokenBuffer("punctuator", "<=", $this->line);
        }elseif($this->reader->current() == "<"){
          $this->reader->pop();
          if($this->reader->current() == "="){
            $this->reader->pop();
            return new TokenBuffer("punctuator", "<<=", $this->line);
          }
          return new TokenBuffer("punctuator", "<<", $this->line);
        }
        return new TokenBuffer("punctuator", "<", $this->line);
      case ">":
        $this->reader->pop();
        if($this->reader->current() == "="){
           $this->reader->pop();
           return new TokenBuffer("punctuator", ">=", $this->line);
        }elseif($this->reader->current() == ">"){
          $this->reader->pop();
          if($this->reader->current() == ">"){
            $this->reader->pop();
            if($this->reader->current() == "="){
              $this->reader->pop();
              return new TokenBuffer("punctuator", ">>>=", $this->line);
            }
            return new TokenBuffer("punctuator", ">>>", $this->line);
          }elseif($this->reader->current() == "="){
            $this->reader->pop();
            return new TokenBuffer("punctuator", ">>=", $this->line);
          }
          return new TokenBuffer("punctuator", ">>", $this->line);
        }
        return new TokenBuffer("punctuator", ">", $this->line);
      case "&":
        $this->reader->pop();
        if($this->reader->current() == "="){
          $this->reader->pop();
          return new TokenBuffer("punctuator", "&=", $this->line);
        }elseif($this->reader->current() == "&"){
          $this->reader->pop();
          return new TokenBuffer("punctuator", "&&", $this->line);
        }
        return new TokenBuffer("punctuator", "&", $this->line);
      case "^":
        $this->reader->pop();
        if($this->reader->current() == "="){
          $this->reader->pop();
          return new TokenBuffer("punctuator", "^=", $this->line);
        }
        return new TokenBuffer("punctuator", "^", $this->line);
      case "|":
        $this->reader->pop();
        if($this->reader->current() == "="){
          $this->reader->pop();
          return new TokenBuffer("punctuator", "|=", $this->line);
        }elseif($this->reader->current() == "|"){
          $this->reader->pop();
          return new TokenBuffer("punctuator", "||", $this->line);
        }
        return new TokenBuffer("punctuator", "|", $this->line);
      case "!":
        $this->reader->pop();
        if($this->reader->current() == "="){
          $this->reader->pop();
          return new TokenBuffer("punctuator", "!=", $this->line);
        }
        return new TokenBuffer("punctuator", "!", $this->line);
      case "~":
        $this->reader->pop();
        return new TokenBuffer("punctuator", "~", $this->line);
      case ",":
        $this->reader->pop();
        return new TokenBuffer("punctuator", ",", $this->line);
    }

    throw new \RuntimeException("Unknown tokens '".$this->reader->index."'(".$this->reader->current().")");
  }

  private function skipJunk(){
    while(true){
      if($this->reader->isLineTerminator()){
        $this->reader->pop();
        $this->line++;
      }elseif($this->reader->isWhiteSpace()){
        $this->reader->pop();
      }else{
        break;
      }
    }
  }

  private function getString(){
    $buffer = "";
    if(($end = $this->reader->current()) == $this->reader->next()){
      $this->reader->next();
      return new TokenBuffer("String", "", $this->line);
    }
    while(!$this->reader->eof()){
      $char = $this->reader->pop();
      if($this->reader->isLineTerminator()){
        throw new \RuntimeException("A string can`t not contain a line terminator!");
      }

      if($char == $end){
        return new TokenBuffer("String", $buffer, $this->line);
      }

      if($char == "\\"){
        switch(($char=$this->reader->pop())){
          case 'b':
            $buffer .= "\b";
          break;
          case 'f':
            $buffer .= "\f";
          break;
          case 'n':
            $buffer .= "\n";
          break;
          case 'r':
            $buffer .= "\r";
          break;
          case 't':
            $buffer .= "\t";
          break;
          case 'x':
            $buffer .= $this->getHex();
          break;
          case 'u':
            for($i=0;$i<4;$i++){
              $buffer .= $this->getHex();
              if($this->reader->pop() != " "){
                throw new \RuntimeException("Missing space after hexdigit");
              }
            }
          break;
          case '0':
            $buffer .= $this->getOctal();
          break;
          default:
            $buffer .= $char;
        }
      }else{
        $buffer .= $char;
      }
    }

    throw new \RuntimeException("Could not get a ".$end." got a end of line");
  }

  private function getIdentify(){
    $builder = "";
    $type = "identify";

    while($this->isIdentifyPart())
      $builder .= $this->reader->pop();

    if($this->isKeyword($builder))
      $type = "keyword";
    else{
      switch($builder){
        case "true":
        case "false":
          $type = "Bool";
        break;
        case "null":
          $type = "Null";
        break;
      }
    }
    return new TokenBuffer($type, $builder, $this->line);
  }

  private function getHex(){
    $digit = "";
    while($this->isHexDigit()){
      $digit .= $this->reader->pop();
    }

    return hexdec($digit);
  }

  private function getOctal(){
    $buffer = "";
    while($this->isOctalDigit()){
      $buffer .= $this->reader->pop();
    }
    return octdec($buffer);
  }

  private function getDigit(){
    if($this->reader->current() == "0"){
      $this->reader->pop();
      if($this->reader->current() == "x" || $this->reader->current() == "X"){
        $this->reader->pop();
        $digit = $this->getHex();
      }else{
        $digit = $this->getOctal();
      }
    }else{
      $digit = $this->getSingleDigit();
      if($this->reader->current() == "."){
        $this->reader->pop();
        $digit .= ".".$this->getSingleDigit();
      }
    }
    return new TokenBuffer("Number", $digit, $this->line);
  }

  private function getSingleDigit(){
    $builder = "";
    while($this->isDigit()){
      $builder .= $this->reader->pop();
    }

    return $builder;
  }

  private function isOctalDigit(){
    $ansii = ord($this->reader->current());
    return $ansii >= 48 && $ansii <= 55;
  }

  private function isHexDigit(){
    if($this->isDigit())
      return true;
    $ansii = ord($this->reader->current());
    return $ansii >= 65 && $ansii <= 70 || $ansii >= 97 && $ansii <= 102;
  }

  private function isDigit(){
    $ansii = ord($this->reader->current());
    return $ansii >= 48 && $ansii <= 57;
  }

  private function isIdentifyPart(){
    return $this->isIdentifyStart() || $this->isDigit();
  }

  private function isIdentifyStart(){
    $ansii = ord($this->reader->current());
    return $ansii >= 65 && $ansii <= 90 || $ansii >= 97 && $ansii <= 122 || $ansii == 36 || $ansii == 95;
  }

  private function isKeyword(string $value) : bool{
    return in_array($value, [
      "break",
      "for",
      "new",
      "var",
      "continue",
      "function",
      "return",
      "void",
      "delete",
      "if",
      "this",
      "while",
      "else",
      "in",
      "typeof",
      "with",
    ]);
  }
}
