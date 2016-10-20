<?php
class AutoLoader{
  private $loads = [];

  public function load($class){
    $arg = explode("\\", $class);
    array_pop($arg);
    $file = dirname(__FILE__)."/".implode("/", $arg).".php";
    if(in_array($file, $this->loads))
      return;
    $this->loads[] = $file;
    include $file;
  }
}
$autoloader = new AutoLoader();
spl_autoload_register([$autoloader, "load"]);

use Types\Objects\Property\Property;
use Types\Value\Value;

function pushTestData(Ecma\Ecma $ecma){
  $globel = $ecma->getCurrentObject();
  $globel->Put("asset", new Property(new Value("Object", new TestData\Test\Test()), ["ReadOnly"]));
  $globel->Put("type", new Property(new Value("Object", new TestData\TestType\Test()), ["ReadOnly"]));
}

function renderDir(string $dir){
  $d = opendir($dir);
  while($item = readdir($d)){
    if($item == "." || $item == ".."){
      continue;
    }

    if(is_dir($dir.$item)){
      renderDir($dir.$item."/");
    }else{
      $ecma = new Ecma\Ecma();
      pushTestData($ecma);
      echo "----".$dir.$item."<br>";
      $ecma->parse($dir.$item, true);
    }
  }

  closedir($d);
}

renderDir("./tests/");
