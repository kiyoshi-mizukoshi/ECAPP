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
$ctg = '';
$dataArr = $_SESSION['detail'];
$id= $_SESSION['item_id'];
$ctg_id=$_SESSION['detail']['ctg_id'];
$ctg = $admin->productsCategory($ctg_id);
var_dump($dataArr);
if(!empty($_POST['submit'])){
  $update = $admin->updateProducts($dataArr,$id);
  header('Location: ' . Bootstrap::ENTRY_URL. 'admin_products_complete.php');

}
$context = [];
$context['confirm'] = $_SESSION['detail'];
$context['ctg'] = $ctg[0]['category_name'];
$template = $twig->loadTemplate('admin_products_confirm.html.twig');
$template->display($context);



