<?php 
namespace shopping;
require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\PDODatabase;
use shopping\lib\Session;
use shopping\lib\Item;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$db_host = $_ENV['DB_HOST'];
$db_name = $_ENV['DB_NAME'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS'];
$db_type = $_ENV['DB_TYPE'];


$db = new PDODatabase($db_host,$db_user,$db_pass,$db_name,$db_type);
$ses = new Session($db);
$itm = new Item($db);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, ['cache' =>Bootstrap::CACHE_DIR]);
$search = (isset($_GET['search'])===true ) ? $_GET['search'] : '';

$searchArr=$itm->getSearchList($search);
if($search==''){
  $searchArr='';
}
$errmsg='';
if($searchArr == ''){
  $errmsg='検索結果がありません。';
}


$context = [];
$context['search'] = $search;
$context['errmsg'] = $errmsg;
$context['searchArr'] = $searchArr;
$template = $twig->loadTemplate('search.html.twig');
$template->display($context);


?>