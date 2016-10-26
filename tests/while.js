var i = 0;
while(true){
  if(i==20){
    break;
  }
  i++;
}

function test(){
  while(true){
    return "hallo";
  }
}

asset(test(), "hallo", "test() should return 'hallo'");
while(true){
  i--;
  if(i==0){
   break;
   print("dont work");
  }
  if(i<5){
   continue;
   print("dont work");
  }
}
