var t = "fuck";
function Plus(start){
  this.number = start;
  
}

function addObject(number){
  this.number += number;
}

function controleObject(expect){
  asset(this.number, expect, "this.number dont return "+expect);
}

Plus.prototype.append = addObject;
Plus.prototype.controle = controleObject;

plus = new Plus(1);
plus.append(1);
plus.controle(2);

t = new Plus(3);
t.controle(3)
plus.controle(2);