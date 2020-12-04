<?php 
namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\PDODatabase;
use shopping\lib\Session;
use shopping\lib\Admin;
use shopping\lib\Item;


$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, ['cache' => Bootstrap::CACHE_DIR]);

if (file_exists(__DIR__ . '/../.env')) {
  $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
  $dotenv->load();
}

$DB_HOST = $_ENV["DB_HOST"];
$DB_DATABASE = $_ENV["DB_DATABASE"];
$DB_USERNAME = $_ENV["DB_USERNAME"];
$DB_PASSWORD = $_ENV["DB_PASSWORD"];
$db_type = 'mysql';

$db = new PDODatabase(
    $DB_HOST,
    $DB_USERNAME,
    $DB_PASSWORD,
    $DB_DATABASE,
    $db_type
);
$ses = new Session($db);
$admin = new Admin($db);
$itm = new Item($db);
$cateArr = $itm->getCategoryList();
$item_id = (isset($_GET['item_id']) === true && preg_match('/^\d+$/' , $_GET['item_id']) === 1) ? $_GET['item_id'] : '';

$msg='';
$detail='';
$detail=$_POST;
if(isset($_POST['submit']) ===true){
  if(preg_match('/^\d+$/' , $detail['price']) === 0)
  {
    $msg='価格は数字で入力してください';
  }else{
    
  }
}

$context = [];
$context['cateArr'] = $cateArr;
$context['msg'] = $msg;
$template = $twig->loadTemplate('admin_products_update.html.twig');
$template->display($context);



