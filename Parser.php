<?php
namespace Parser;

use Reader\Reader;
use Ecma\Ecma;
use Types\Completion\Completion;
use Token\TokenCreater\TokenCreater;
use Expresion\BaseExpresion\BaseExpresion;
use Expresion\Expresion\Expresion;
use Expresion\AssignExpresion\AssignExpresion;
use Expresion\ConditionalExpresion\ConditionalExpresion;
use Expresion\BitwiseExpresion\BitwiseExpresion;
use Expresion\EqualityExpresion\EqualityExpresion;
use Expresion\UnaryExpresion\UnaryExpresion;
use Expresion\NumberExpresion\NumberExpresion;
use Expresion\IdentifyExpresion\IdentifyExpresion;
use Expresion\RelationalExpresion\RelationalExpresion;
use Expresion\ObjectExpresion\ObjectExpresion;
use Expresion\StringExpresion\StringExpresion;
use Expresion\ArrayExpresion\ArrayExpresion;
use Expresion\NewExpresion\NewExpresion;
use Expresion\LogicalExpresion\LogicalExpresion;
use Expresion\BoolExpresion\BoolExpresion;
use Expresion\NullExpresion\NullExpresion;
use Expresion\ThisExpresion\ThisExpresion;
use Expresion\CallExpresion\CallExpresion;

class Parser{
  private $reader;
  private $token;
  private $allow_call;

  public function __construct($code){
    $this->reader = new Reader($code);
    $this->token = new TokenCreater($this->reader);
    $this->allow_call = new \Stack\Stack();
    $this->allow_call->push(true);
  }

  public function parse(Ecma $ecma){
    while($this->token->currentToken()->type != "EOF"){
      $com = $this->parseStatment($ecma);
      if(!$com->isNormal()){
        return $com;
      }
    }
    return new Completion(Completion::NORMAL);
  }

  private function parseStatment(Ecma $ecma) : Completion{
    $token = $this->token->currentToken();
    if($token->type == "punctuator"){
      switch($token->value){
        case ";":
          $this->token->next();
          return new Completion(Completion::NORMAL);
      }
    }elseif($token->type == "keyword"){
      switch($token->value){
        case "var":
         $line = $this->token->currentToken()->line;
         $this->token->next();
         $this->parseVariableDeclarationList()->parse($ecma);
         if($this->token->currentToken()->line == $line){
           if($this->token->currentToken()->type != "punctuator" || $this->token->currentToken()->value != ";"){
             throw new RuntimeException("Missing ;");
           }
           $this->token->next();
         }
         return new Completion(Completion::NORMAL);
      }
    }
    return $this->expresionStatment($ecma);
  }

  private function expresionStatment(Ecma $ecma) : Completion{
    $line = $this->token->currentToken()->line;
    $expresion = $this->expresion();
    if($line == $this->token->currentToken()->line){
      if($this->token->currentToken()->type != "punctuator" || $this->token->currentToken()->value != ";"){
        exit($this->token->currentToken()->value."(".$this->token->currentToken()->type.")");
        throw new \RuntimeException("Missing ;");
      }
      $this->token->next();
    }
     return new Completion(Completion::NORMAL, $expresion->parse($ecma));
  }

  public function expresion() : BaseExpresion{
    $value = [$this->parseAssignmentExpression()];
    while($this->token->currentToken()->type == "punctuator" && $this->token->currentToken()->value == ","){
      $this->token->next();
      $value[] = $this->parseAssignmentExpression();
    }
    if(count($value) == 1)
      return $value[0];
    return new Expresion($value);
  }

  private function parseVariableDeclarationList(){
    $value = [$this->parseVariabelDeclaration()];
    while($this->token->currentToken()->type == "punctuator" && $this->token->currentToken()->value == ","){
      $this->token->next();
      $value[] = $this->parseVariabelDeclaration();
    }

    return new Expresion($value);
  }

  private function parseVariabelDeclaration(){
    $this->expect("identify");
    $identify = $this->token->currentToken()->value;
    $this->token->next();
    $this->expect("punctuator", "=");
    $this->token->next();
    return new AssignExpresion(new IdentifyExpresion($identify), "=", $this->parseAssignmentExpression());
  }

  private function parseAssignmentExpression(){
    $value = $this->parseConditionalExpression();
    if($this->token->currentToken()->type == "punctuator"){
      switch(($v = $this->token->currentToken()->value)){
        case "=":
        case "*=":
        case "/=":
        case "%=":
        case "+=":
        case "-=":
        case "<<=":
        case ">>=":
        case ">>>=":
        case "&=":
        case "^=":
        case "|=":
          $this->token->next();
          $value = new AssignExpresion($value, $v, $this->parseAssignmentExpression());
      }
    }
    return $value;
  }

  private function parseConditionalExpression(){
    $value = $this->parseLogicalORExpression();
    if($this->token->currentToken()->type == "punctuator" && $this->token->currentToken()->value == "?"){
      $this->token->next();
      $arg1 = $this->parseAssignmentExpression();
      $this->expect("punctuator", ":");
      $this->token->next();
      $arg2 = $this->parseAssignmentExpression();
      return new ConditionalExpresion($value, $arg1, $arg2);
    }
    return $value;
  }

  private function parseLogicalORExpression(){
    $value = $this->parseLogicalANDExpression();
    if($this->token->currentToken()->type == "punctuator" && $this->token->currentToken()->value == "||"){
      $this->token->next();
      $value = new LogicalExpresion($value, "||", $this->parseLogicalORExpression());
    }

    return $value;
  }

  private function parseLogicalANDExpression(){
    $value = $this->parseBitwiseORExpression();
    if($this->token->currentToken()->type == "punctuator" && $this->token->currentToken()->value == "&&"){
      $this->token->next();
      $value = new LogicalExpresion($value, "&&", $this->parseLogicalANDExpression());
    }

    return $value;
  }

  private function parseBitwiseORExpression(){
    $value = $this->parseBitwiseXORExpression();
    if($this->token->currentToken()->type == "punctuator" && $this->token->currentToken()->value == "|"){
      $this->token->next();
      $value = new BitwiseExpresion($value, "|", $this->parseBitwiseORExpression());
    }
    return $value;
  }

  private function parseBitwiseXORExpression(){
    $value = $this->parseBitwiseANDExpression();
    if($this->token->currentToken()->type == "punctuator" && $this->token->currentToken()->value == "^"){
      $this->token->next();
      $value = new BitwiseExpresion($value, "^", $this->parseBitwiseXORExpression());
    }
    return $value;
  }

  private function parseBitwiseANDExpression(){
    $value = $this->parseEqualityExpression();
    if($this->token->currentToken()->type == "punctuator" && $this->token->currentToken()->value == "&"){
      $this->token->next();
      $value = new BitwiseExpresion($value, "&", $this->parseBitwiseANDExpression());
    }

    return $value;
  }

  private function parseEqualityExpression(){
    $value = $this->parseRelationalExpression();
    if($this->token->currentToken()->type == "punctuator"){
      switch($v = $this->token->currentToken()->value){
        case "==":
        case "!=":
          $this->token->next();
          $value = new EqualityExpresion($value, $v, $this->parseEqualityExpression());
      }
    }
    return $value;
  }

  private function parseRelationalExpression(){
    $value = $this->parseShiftExpression();
    if($this->token->currentToken()->type == "punctuator"){
      switch(($v = $this->token->currentToken()->value)){
        case "<":
        case ">":
        case "<=":
        case ">=":
          $this->token->next();
          $value = new RelationalExpresion($value, $v, $this->parseRelationalExpression());
      }
    }

    return $value;
  }

  private function parseShiftExpression(){
    $value = $this->parseAdditiveExpression();
    if($this->token->currentToken()->type == "punctuator"){
      switch(($v = $this->token->currentToken()->value)){
        case "<<":
        case ">>":
        case ">>>":
          $this->token->next();
          $value = new BitwiseExpresion($value, $v, $this->parseShiftExpression());
      }
    }

    return $value;
  }

  private function parseAdditiveExpression(){
    $value = $this->parseMultiplicativeExpression();
    if($this->token->currentToken()->type == "punctuator"){
      switch(($v = $this->token->currentToken()->value)){
        case "+":
        case "-":
          $this->token->next();
          return new BitwiseExpresion($value, $v, $this->parseAdditiveExpression());
      }
    }
    return $value;
  }

  private function parseMultiplicativeExpression(){
    $value = $this->parseUnaryExpression();

    if($this->token->currentToken()->type == "punctuator"){
      switch(($v = $this->token->currentToken()->value)){
        case "*":
        case "/":
        case "%":
          $this->token->next();
          return new BitwiseExpresion($value, $v, $this->parseMultiplicativeExpression());
      }
    }

    return $value;
  }

  private function parseUnaryExpression(){
    $token = $this->token->currentToken();
    if($token->type == "keyword"){
      switch($token->value){
        case "typeof":
        case "void":
        case "delete":
          $this->token->next();
          return new UnaryExpresion($this->parsePostfixExpression(), $token->value);
      }
    }elseif($token->type == "punctuator"){
      switch($token->value){
        case "++":
        case "--":
        case "+":
        case "-":
        case "~":
        case "!":
          $this->token->next();
          return new UnaryExpresion($this->parsePostfixExpression(), $token->value);
      }
    }
    return $this->parsePostfixExpression();
  }

  private function parsePostfixExpression(){
    $value = $this->parseLeftHandSide();
    if($this->token->currentToken()->type == "punctuator"){
      if($this->token->currentToken()->value == "++"){
        $this->token->next();
        return new UnaryExpresion($value, "++");
      }elseif($this->token->currentToken()->value == "--"){
        $this->token->next();
        return new UnaryExpresion($value, "--");
      }
    }
    return $value;
  }

  private function parseLeftHandSide(){
    $value = $this->token->currentToken()->type == "keyword" && $this->token->currentToken()->value == "new" ? $this->parseNewExpresion() : $this->parsePrimerieExpression();
    while($this->token->currentToken()->type == "punctuator"){
      if($this->token->currentToken()->value == "."){
        if($this->token->next()->type != "identify"){
          throw new RuntimeException("Unexpected ".$this->token->currentToken()->type);
        }

        $identify = new IdentifyExpresion($this->token->currentToken()->value);
        $this->token->next();
        $value = new ObjectExpresion($value, $identify);
      }elseif($this->token->currentToken()->value == "["){
        $this->token->next();
        $expresion = $this->expresion();
        $this->expect("punctuator", "]");
        $this->token->next();
        $value = new ArrayExpresion($value, $expresion);
      }elseif($this->token->currentToken()->value == "(" && $this->allow_call->peek()){
        $this->token->next();
        $value = new CallExpresion($value, $this->getAgument());
      }else{
        break;
      }
    }
    return $value;
  }

  private function parseNewExpresion(){
    $this->token->next();
    $this->allow_call->push(false);
    $name = $this->parseLeftHandSide();
    $this->allow_call->pop();
    $this->expect("punctuator", "(");
    $this->token->next();
    $arguments = $this->getAgument();
    return new NewExpresion($name, $arguments);
  }

  private function parsePrimerieExpression(){
    $token = $this->token->currentToken();
    if($token->type == "identify"){
      $this->token->next();
      return new IdentifyExpresion($token->value);
    }

    if($token->type == "keyword" && $token->value == "this"){
      $this->token->next();
      return new ThisExpresion();
    }

    if($token->type == "punctuator" && $token->value == "("){
       $this->token->next();
       $expresion = $this->expresion();
       $this->expect("punctuator", ")");
       return $expresion;
    }

    if($token->type == "Bool"){
      $this->token->next();
      return new BoolExpresion($token->value);
    }

    if($token->type == "Null"){
      $this->token->next();
      return new NullExpresion();
    }

    if($token->type == "Number"){
      $this->token->next();
      return new NumberExpresion((int)$token->value);
    }

    if($token->type == "String"){
      $this->token->next();
      return new StringExpresion($token->value);
    }
    exit($token->value."|".$token->type);
    throw new \RuntimeException("Unexpected type ".$token->type);
  }

  private function getAgument(){
    if($this->token->currentToken()->type == "punctuator" && $this->token->currentToken()->value == ")"){
      $this->token->next();
      return [];
    }
    $args = [$this->parseAssignmentExpression()];
    while($this->token->currentToken()->type == "punctuator" && $this->token->currentToken()->value == ","){
      $this->token->next();
      $args[] = $this->parseAssignmentExpression();
    }

    $this->expect("punctuator", ")");
    $this->token->next();

    return $args;
  }

  private function expect(string $type, $value = null){
    $token = $this->token->currentToken();
    if($token->type != $type){
      throw new \RuntimeException("Unkexpected ".$token->type);
    }

    if($value != null && $value != $token->value){
      exit($type."-".$token->type."|".$value."-".$token->value);
      throw new \RuntimeException("Unkexpected ".$token->type);
    }
  }
}