<?php 
namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\PDODatabase;
use shopping\lib\Session;
use shopping\lib\Admin;


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
$checkDate = '';
$dataArr = '';
$aggregate = (isset($_POST['aggregate']) === true) ? $_POST : '';
if(!empty($aggregate)){
  $checkDate = $admin->checkDate($aggregate);
  if($checkDate===true)
  {
    $dataArr= $admin->selectData($aggregate);
    $checkDate='';
    if(empty($dataArr)){
      $checkDate = '<span class="err">その期間に売上はありませんでした</span>';
    }
  }
}
$context = [];
$context['checkdate']=$checkDate;
$context['dataArr'] = $dataArr;
$template = $twig->loadTemplate('admin_shopping.html.twig');
$template->display($context);



