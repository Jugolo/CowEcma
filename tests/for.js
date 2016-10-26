for(i=0;i<10;i++){
  if(i>10)
    print("fail");
}

for(i=0;i<10;i++){
  if(i<5)
    continue;
  else
    break;
}

function baseTest(){
  for(i=0;i<10;i++)
    return "work";
  return "failed";
}

asset(baseTest(), "work", "base test failed");

function test(){
  for(i=0;i<10;i++){
    if(5<i)
      return "success";
  }
  return "failed";
}
asset(test(), "success", "test() should return 'success'");

for(var i=0;i<10;i++){

}

function test(){
  this.one = "1";
  this.two = "2";
  this.tree = "3";
}

for(key in new test()){
  if(key != "one" && key != "two" && key != "tree"){
    print("Fail to for in");
  }
}
