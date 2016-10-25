function add(number1, number2){
  return number1+number2;
}

asset(add(1, 2, "this is a agumemt"), 3, "1+2=3!");
asset(Function("number1", "number2", "return number1+number2;")(1,2), 3, "1+2=3");
asset(new Function("number1", "number2", "return number1+number2;")(1,2), 3, "1+2=3");

function testAgument(one, two, result){
  asset(one+two, result, "agument failed");
  asset(arguments.one+arguments.two, arguments.result, "aguments failed");
  asset(arguments[0]+arguments[1], arguments[2], "arguments failed");
}
testAgument(1,2,3);