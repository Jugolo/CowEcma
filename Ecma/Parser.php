<?php
namespace Ecma\Parser;

use Ecma\Reader\Reader;
use Ecma\Ecma\Ecma;
use Ecma\Types\Completion\Completion;
use Ecma\Token\TokenCreater\TokenCreater;
use Ecma\Expresion\BaseExpresion\BaseExpresion;
use Ecma\Expresion\Expresion\Expresion;
use Ecma\Expresion\AssignExpresion\AssignExpresion;
use Ecma\Expresion\ConditionalExpresion\ConditionalExpresion;
use Ecma\Expresion\BitwiseExpresion\BitwiseExpresion;
use Ecma\Expresion\EqualityExpresion\EqualityExpresion;
use Ecma\Expresion\UnaryExpresion\UnaryExpresion;
use Ecma\Expresion\NumberExpresion\NumberExpresion;
use Ecma\Expresion\IdentifyExpresion\IdentifyExpresion;
use Ecma\Expresion\RelationalExpresion\RelationalExpresion;
use Ecma\Expresion\ObjectExpresion\ObjectExpresion;
use Ecma\Expresion\StringExpresion\StringExpresion;
use Ecma\Expresion\ArrayExpresion\ArrayExpresion;
use Ecma\Expresion\NewExpresion\NewExpresion;
use Ecma\Expresion\LogicalExpresion\LogicalExpresion;
use Ecma\Expresion\BoolExpresion\BoolExpresion;
use Ecma\Expresion\NullExpresion\NullExpresion;
use Ecma\Expresion\ThisExpresion\ThisExpresion;
use Ecma\Expresion\CallExpresion\CallExpresion;
use Ecma\Expresion\FunctionExpresion\FunctionExpresion;
use Ecma\Expresion\EmptyExpresion\EmptyExpresion;
use Ecma\Statment\Statment\Statment;
use Ecma\Statment\EmptyStatment\EmptyStatment;
use Ecma\Statment\VarStatment\VarStatment;
use Ecma\Statment\FunctionStatment\FunctionStatment;
use Ecma\Statment\ExpresionStatment\ExpresionStatment;
use Ecma\Statment\ReturnStatment\ReturnStatment;
use Ecma\Statment\BlockStatment\BlockStatment;
use Ecma\Statment\IfStatment\IfStatment;
use Ecma\Statment\WhileStatment\WhileStatment;
use Ecma\Statment\BreakStatment\BreakStatment;
use Ecma\Statment\ContinueStatment\ContinueStatment;
use Ecma\Statment\ForStatment\ForStatment;
use Ecma\Statment\WithStatment\WithStatment;
use Ecma\Stack\Stack;

class Parser{
  private $reader;
  private $token;
  private $allow_call;
  private $ecma;

  public function __construct($code){
    $this->reader = new Reader($code);
    $this->token = new TokenCreater($this->reader);
    $this->allow_call = new Stack();
    $this->allow_call->push(true);
  }

  public function parse(Ecma $ecma){
    $this->ecma = $ecma;
    while($this->token->currentToken()->type != "EOF"){
      $com = $this->parseStatment()->parse($ecma);
      if(!$com->isNormal()){
        return $com;
      }
    }
    return new Completion(Completion::NORMAL);
  }

  private function parseStatment() : Statment{
    $token = $this->token->currentToken();
    if($token->type == "punctuator"){
      switch($token->value){
        case ";":
          $this->token->next();
          return new EmptyStatment();
        case "{":
          return $this->getStatmentBlock();
      }
    }elseif($token->type == "keyword"){
      switch($token->value){
        case "var":
         $line = $this->token->currentToken()->line;
         $this->token->next();
         $var = new VarStatment($this->parseVariableDeclarationList());
         if($this->token->currentToken()->line == $line){
           if($this->token->currentToken()->type != "punctuator" || $this->token->currentToken()->value != ";"){
             throw new \RuntimeException("Missing ;");
           }
           $this->token->next();
         }
         return $var;
         case "function":
           return $this->createFunction();
        case "return":
           $line = $this->token->currentToken()->line;
           $this->token->next();
           $expresion = $this->expresion();
           if($this->token->currentToken()->line == $line){
             if($this->token->currentToken()->type != "punctuator" || $this->token->currentToken()->value != ";"){
               throw new RuntimeException("Missing ;");
             }
             $this->token->next();
           }

           return new ReturnStatment($expresion);
        case "if":
           return $this->getIf();
        case "while":
           return $this->getWhile();
        case "break";
           $this->token->next();
           $this->expect("punctuator", ";");
           $this->token->next();
           return new BreakStatment();
        case "continue";
           $this->token->next();
           $this->expect("punctuator", ";");
           $this->token->next();
           return new ContinueStatment();
        case "for":
           return $this->getFor();
        case "with":
           return $this->getWith();
      }
    }
    return $this->expresionStatment();
  }

  private function getWith(){
    $this->token->next();
    $this->expect("punctuator", "(");
    $expresion = $this->expresion();
    $this->expect("punctuator", ")");
    $this->token->next();
    return new WithStatment($expresion, $this->parseStatment());
  }

  private function getFor(){
    $expresion = [];
    $this->token->next();
    $this->expect("punctuator", "(");
    if($this->token->next()->type == "punctuator" && $this->token->currentToken()->value == ";"){
      $expresion[] = null;
      $this->token->next();
    }else{
      if($this->token->currentToken()->type == "keyword" && $this->token->currentToken()->value == "var"){
        $this->token->next();
        $expresion[] = new VarStatment($this->parseVariableDeclarationList());
      }else
        $expresion[] = $this->expresion();
        if($this->token->currentToken()->type != "keyword" || $this->token->currentToken()->value != "in"){
         $this->expect("punctuator", ";");
         $this->token->next();
        }
    }

    if($expresion[0] !== null && $this->token->currentToken()->type == "keyword" && $this->token->currentToken()->value == "in"){
      $this->token->next();
       $expresion[] = $this->expresion();
    }else{
      if($this->token->currentToken()->type == "punctuator" && $this->token->currentToken()->value == ";"){
        $this->token->next();
        $expresion[] = null;
      }else{
        $expresion[] = $this->expresion();
        $this->expect("punctuator", ";");
        if($this->token->next()->type == "punctuator" && $this->token->currentToken()->value == ")"){
         $expresion[] = null;
       }else{
          $expresion[] = $this->expresion();
       }
      }
    }
    $this->expect("punctuator", ")");
    $this->token->next();
    return new ForStatment($expresion, $this->parseStatment());
  }

  private function getWhile(){
    $this->token->next();
    $this->expect("punctuator", "(");
    $this->token->next();
    $expresion = $this->expresion();
    $this->expect("punctuator", ")");
    $this->token->next();
    return new WhileStatment($expresion, $this->parseStatment());
  }

  private function getStatmentBlock(){
     $statment = [];
     $this->token->next();
     while($this->token->currentToken()->type != "EOF" && $this->token->currentToken()->type != "punctuator" || $this->token->currentToken()->value != "}"){
        $statment[] = $this->parseStatment();
     }
     $this->expect("punctuator", "}");
     $this->token->next();
     return new BlockStatment($statment);
  }

  private function getIf(){
    $this->token->next();
    $this->expect("punctuator", "(");
    $this->token->next();
    $expresion = $this->expresion();
    $this->expect("punctuator", ")");
    $this->token->next();
    $true = $this->parseStatment();
    if($this->token->currentToken()->type == "keyword" && $this->token->currentToken()->value == "else"){
      $this->token->next();
      $false = $this->parseStatment();
    }else
      $false = new EmptyStatment();
    return new IfStatment($expresion, $true, $false);
  }

  private function getBlock(){
    $arg = "";
    $count = 1;
    while(true){
      if($this->reader->current() == "{"){
        $count++;
      }elseif($this->reader->current() == "}"){
        $count--;
        if($count == 0){
          $this->reader->next();
          return $arg;
        }
      }
      $arg .= $this->reader->current();
      $this->reader->next();
    }

  }

  private function createFunction() : Statment{
    $this->token->next();
    $this->expect("identify");
    $name = $this->token->currentToken();
    $this->token->next();
    $this->expect("punctuator", "(");
    $args = [];
    if($this->token->next()->type != "punctuator" && $this->token->currentToken()->value != ")"){
      $this->expect("identify");
      $args[] = $this->token->currentToken()->value;
      while($this->token->next()->type == "punctuator" && $this->token->currentToken()->value == ","){
        $this->token->next();
        $this->expect("identify");
        $args[] = $this->token->currentToken()->value;
      }
    }
    $this->expect("punctuator", ")");
    $this->token->next();
    $this->expect("punctuator", "{");
    $block = $this->getBlock();
    $this->token->next();
    return new FunctionStatment(new FunctionExpresion($name->value, $args, $block, $this->ecma));
  }

  private function expresionStatment() : Statment{
    $line = $this->token->currentToken()->line;
    $expresion = $this->expresion();
    if($line == $this->token->currentToken()->line){
      if($this->token->currentToken()->type != "punctuator" || $this->token->currentToken()->value != ";"){
        throw new \RuntimeException("Missing ;");
      }
      $this->token->next();
    }
     return new ExpresionStatment($expresion);
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
    if($this->token->next()->type == "punctuator" && $this->token->currentToken()->value == "="){
       $this->token->next();
       $value = $this->parseAssignmentExpression();
    }else{
       $value = new EmptyExpresion();
    }
 
    return new AssignExpresion(new IdentifyExpresion($identify), "=", $value);
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
