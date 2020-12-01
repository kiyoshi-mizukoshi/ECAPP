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
$msg = '';
$newcategory='';
$newcategory=$_POST;
if(isset($_POST['submit']) ===true){
  $category = $admin->categoryAdd($newcategory);
  if($category===true)
  {
    $msg = 'カテゴリーを追加しました';
  }else{
    $msg = 'カテゴリーを追加できませんでした';
  }
}


$cateArr = $itm->getCategoryList();

$context = [];
$context['cateArr'] = $cateArr;
$context['msg'] = $msg;
$template = $twig->loadTemplate('admin_category.html.twig');
$template->display($context);



