<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\PDODatabase;
use shopping\lib\Session;
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
$customer_no = $_SESSION['id'];
if (isset($_SESSION['id'])) {//ログインしているとき
  $name =  $_SESSION['name'] . 'さん';
  $link = '<a href="logout.php" class="header-nav-item-link">ログアウト</a>';
  $regist = '';
  $gest = '';
} else {//ログインしていない時
  $name = '<a class="header-nav-item-link" href="login_form.php">Login</a>';
  $link = '<a class="header-nav-item-link" href="login_form.php">ログイン</a>';
  $gest ='<a class="header-nav-item-link" href="gestlogin.php">ゲストログイン</a>';
  $regist = '<a class="header-nav-item-link" href="regist.php">会員登録</a>';
}


$sumNum = $_SESSION['sumnum'];
$sumPrice = $_SESSION['sumprice'];
$dataArr = $_SESSION['cartdata'];
foreach($dataArr as $value)
{
  $value+=['customor_no' => $customer_no,
            'regist_date'=>date("Y-m-d H:i:s")];
  unset($value['crt_id']);
if(!empty($_POST['buy']))
{
  $buy = $cart->buyCart($value);//商品を購入する
  $res = $cart->AllDelCartData($customer_no);//カートを削除する
  header('Location: ' . Bootstrap::ENTRY_URL. 'cart_complete.php');
}
}

$context = [];
$context['sumNum'] = $sumNum;
$context['sumPrice'] = $sumPrice;
$context['dataArr'] = $dataArr;
$context['name'] = $name;
$context['gest'] = $gest;
$context['link'] = $link;
$context['regist'] = $regist;
$context['cateArr'] = $_SESSION['cateArr'];

$template = $twig->loadTemplate('cart_confirm.html.twig');
$template->display($context);

?>