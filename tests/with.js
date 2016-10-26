function Test(){
  this.number = 0;
}

function TestAdd(number){
  this.number += number;
}

function TestControle(expect){
  asset(this.number, expect, "Number is not "+expect);
}

Test.prototype.add = TestAdd;
Test.prototype.controle = TestControle;

var test = new Test();
with(test){
  add(1);
  controle(1);
  add(2);
  controle(3);
}
