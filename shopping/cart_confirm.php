<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\PDODatabase;
use shopping\lib\Session;
use shopping\lib\Cart;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$db_host = $_ENV['DB_HOST'];
$db_name = $_ENV['DB_NAME'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS'];
$db_type = $_ENV['DB_TYPE'];

$db = new PDODatabase($db_host,$db_user,$db_pass,$db_name,$db_type);
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
$template = $twig->loadTemplate('cart_confirm.html.twig');
$template->display($context);

?>