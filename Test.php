<?php
function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function diagram(array $item, $file){
  $item = array_filter($item, function($value) { return $value !== ''; });
  $imgage = imagecreatetruecolor(1000, 500);
  $width = 1000/count($item);
  $max = max($item);
  imagecreatetruecolor(120, 20);
  $text = imagecolorallocate($imgage, 233, 14, 91);
  for($i=0;$i<count($item)-1;$i++){
    imageline($imgage, $i*$width, ((($item[$i]/$max)*100)/500)*100, ($i+1)*$width, ((($item[$i+1]/$max)*100)/500)*100, $text);
  }
  imagestring($imgage, 5, 10, 480, "Min: ".min($item)." Max: ".$max." Test size: ".count($item), $text);
  imagepng($imgage, $file);
  imagedestroy($imgage);
}

class TimeCache{
  private static $buffer = [];

  public static function append(string $file, $time){
    self::$buffer[substr($file, 1)] = $time;
  }

  public static function save(){
    foreach(self::$buffer as $name => $time){
      $name = substr($name, 0, strrpos($name, "."));
      if(!is_dir("TestFolder.".dirname($name))){
        @mkdir("TestFolder".dirname($name));
      }
      $fopen = fopen("TestFolder".$name.".log", "a+");
      fwrite($fopen, $time."\r\n");
      fclose($fopen);
      diagram(explode("\r\n", file_get_contents("TestFolder".$name.".log")), "TestFolder".$name.".png");
    }
  }
}

class AutoLoader{
  private $loads = [];

  public function load($class){
    $arg = explode("\\", $class);
    array_pop($arg);
    $dir = implode("/", $arg);
    if($dir == "")
      return;
    $file = dirname(__FILE__)."/".$dir.".php";
    if(in_array($file, $this->loads))
      return;
    if(!file_exists($file)){
      throw new Exception("Unknown file: ".$file);
    }
    $this->loads[] = $file;
    include $file;
  }

  public function getFiles(){
    return $this->loads;
  }
}
$autoloader = new AutoLoader();
spl_autoload_register([$autoloader, "load"]);

use Ecma\Types\Objects\Property\Property;
use Ecma\Types\Value\Value;

function pushTestData(Ecma\Ecma\Ecma $ecma){
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
      $ecma = new Ecma\Ecma\Ecma();
      pushTestData($ecma);
      echo "----".$dir.$item;
      $ecma->parse($dir.$item, true);
      $time = microtime_float()-$start;
      TimeCache::append($dir.$item, $time);
      echo "(".$time.")<br>\r\n";
    }
  }

  closedir($d);
}
$start = microtime_float();
renderDir("./tests/");
$all = microtime_float()-$start;
$fopen = fopen("TestFolder/all.log", "a+");
fwrite($fopen, $all."\r\n");
fclose($fopen);
diagram(explode("\r\n", file_get_contents("TestFolder/all.log")), "TestFolder/all.png");
TimeCache::save();
echo "---<br>\r\nIn all: ".(microtime_float()-$start)."\r\n<br>---";
echo "<hr>";
echo "<h3>Files there is used:</h3>";
echo implode("<br>\r\n", $autoloader->getFiles());
