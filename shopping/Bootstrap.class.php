<?php 

namespace shopping;

date_default_timezone_set('Asia/Tokyo');//時間設定を日本にする

if (file_exists(__DIR__ . '/../.env')) {
  require_once '../vendor/autoload.php';

  class Bootstrap
  {
    const APP_DIR = '/Applications/MAMP/htdocs/ECAPP/';
    // const APP_DIR = "/app/";
  
    const TEMPLATE_DIR = self::APP_DIR . 'templates/shopping/';
  
    const  CACHE_DIR = self::APP_DIR . 'templates_c/shopping/';
  
    const APP_URL = 'http://localhost:8888/ECAPP/';
  
    const ENTRY_URL = self::APP_URL . 'shopping/';
  
    public static function loadClass($class)
    {
      $path = str_replace('\\','/',self::APP_DIR . $class . '.class.php');
      require_once $path;
    }
  }
  

}else {
  require_once '/app/vendor/autoload.php';

  class Bootstrap
  {
    // const APP_DIR = '/Applications/MAMP/htdocs/ECAPP/';
    const APP_DIR = "/app/";
  
    const TEMPLATE_DIR = self::APP_DIR . 'templates/shopping/';
  
    const  CACHE_DIR = self::APP_DIR . 'templates_c/shopping/';
  
    const APP_URL = '';
  
    const ENTRY_URL = '';
  
    public static function loadClass($class)
    {
      $path = str_replace('\\','/',self::APP_DIR . $class . '.class.php');
      require_once $path;
    }
  }
  
}


//これを実行しないとオートローダーとして動かない
spl_autoload_register([
  'shopping\Bootstrap',
  'loadClass'
]);


