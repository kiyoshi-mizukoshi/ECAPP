<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\PDODatabase;
use shopping\lib\Session;
use shopping\lib\Favorite;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$db_host = $_ENV['DB_HOST'];
$db_name = $_ENV['DB_NAME'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS'];
$db_type = $_ENV['DB_TYPE'];

$db = new PDODatabase($db_host,$db_user,$db_pass,$db_name,$db_type);
$ses = new Session($db);
$favorite = new Favorite($db);

//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, ['cache' =>Bootstrap::CACHE_DIR]);

//セッションに、セッションIDを設定する
//$ses->checkSession();いれなくてもいい
//ログインされていなければ、ログイン画面に飛ばす
if(isset($_SESSION['id'])=='')
{
  header('Location: ' . Bootstrap::ENTRY_URL. 'login_form.php');

}
$item_id = (isset($_GET['item_id']) === true && preg_match('/^\d+$/' , $_GET['item_id']) === 1) ? $_GET['item_id'] : '';
$customer_no = $_SESSION['id'];

if($item_id !== '') {
  $res = $favorite->deleteFavorite($customer_no, $item_id);
}


$dataArr = $favorite->getFavoriteData($customer_no);
var_dump($dataArr);

$context = [];
$context['dataArr'] = $dataArr;
$template = $twig->loadTemplate('favorite.html.twig');
$template->display($context);

?>