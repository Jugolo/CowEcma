eval("function test(){return 1;}asset(test(), 1, 'test() should return 1');");
var func = eval("function test(){return 2;}return test;");
asset(func(), 2, "func should return 2");
