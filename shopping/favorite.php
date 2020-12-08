<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\PDODatabase;
use shopping\lib\Session;
use shopping\lib\Favorite;
use shopping\lib\Cart;

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
$favorite = new Favorite($db);
$cart = new Cart($db);
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

if (isset($_SESSION['id'])) {//ログインしているとき
  $name =  $_SESSION['name']. 'さん';
  $link = '<a href="logout.php" class="header-nav-item-link">ログアウト</a>';
  $regist = '';
  $gest = '';
} else {//ログインしていない時
  $name = '<a class="header-nav-item-link" href="login_form.php">Login</a>';
  $link = '<a class="header-nav-item-link" href="login_form.php">ログイン</a>';
  $gest ='<a class="header-nav-item-link" href="gestlogin.php">ゲストログイン</a>';
  $regist = '<a class="header-nav-item-link" href="regist.php">会員登録</a>';
}

$item_id = (isset($_GET['item_id']) === true && preg_match('/^\d+$/' , $_GET['item_id']) === 1) ? $_GET['item_id'] : '';
$customer_no = $_SESSION['id'];
$num = (isset($_GET['num']) === true && preg_match('/^\d+$/' , $_GET['num']) === 1) ? $_GET['num'] : '';

if($num !== '' && $customer_no !=='')
{
  $count = $cart->countData($customer_no, $item_id);
  if($count[0]['item_id']>0){
    $res = $cart->updateCartData($customer_no, $item_id,$num);
    header('Location: ' . Bootstrap::ENTRY_URL. 'cart.php');

  }else{
    $res = $cart->insCartData2($customer_no, $item_id,$num);
    header('Location: ' . Bootstrap::ENTRY_URL. 'cart.php');
  
  }
}

if($item_id !== '' && $num === '') {
  $res = $favorite->deleteFavorite($customer_no, $item_id);
}


$dataArr = $favorite->getFavoriteData($customer_no);


$context = [];
$context['dataArr'] = $dataArr;
$context['name'] = $name;
$context['gest'] = $gest;
$context['link'] = $link;
$context['regist'] = $regist;
$context['cateArr'] = $_SESSION['cateArr'];
$template = $twig->loadTemplate('favorite.html.twig');
$template->display($context);

?>