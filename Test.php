<?php
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
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

  public function getFiles(){
    return $this->loads;
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
  $ecma->pushVariabel("echo", new Value("Object", new TestData\EchoTest\EchoTest()));
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
      $start = microtime_float();
      $ecma = new Ecma\Ecma();
      pushTestData($ecma);
      echo "----".$dir.$item;
      $ecma->parse($dir.$item, true);
      echo "(".(microtime_float()-$start).")<br>\r\n";
    }
  }

  closedir($d);
}
$start = microtime_float();
renderDir("./tests/");
echo "---<br>\r\nIn all: ".(microtime_float()-$start)."\r\n<br>---";
echo "<hr>";
echo "<h3>Files there is used:</h3>";
echo implode("<br>\r\n", $autoloader->getFiles());
