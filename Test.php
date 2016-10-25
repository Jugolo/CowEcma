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
  $ecma->pushVariabel("asset", new Value("Object", new TestData\Test\Test()));
  $ecma->pushVariabel("type", new Value("Object", new TestData\TestType\Test()));
  $ecma->pushVariabel("print", new Value("Object", new TestData\TestPrint\TestPrint()));
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
      echo "----".$dir.$item."<br>\r\n";
      $ecma->parse($dir.$item, true);
    }
  }

  closedir($d);
}

renderDir("./tests/");
