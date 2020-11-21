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

//item_idを取得する
$item_id = (isset($_GET['item_id']) === true && preg_match('/^\d+$/' , $_GET['item_id']) === 1) ? $_GET['item_id'] : '';
$crt_id = (isset($_GET['crt_id']) === true && preg_match('/^\d+$/' , $_GET['crt_id']) === 1) ? $_GET['crt_id'] : '';

//item_idが設定されていれば、ショッピングカートに登録する
if($item_id !== '') {
  $res = $cart->insCartData($customer_no, $item_id);
  header('Location: ' . Bootstrap::ENTRY_URL. 'cart.php');

  //登録に失敗した場合、エラーページを表示する
  if($res === false) {
    echo "商品購入に失敗しました";
    exit();
  }
  
}
if(!empty($_POST['remove']))
{  
  $res = $cart->AllDelCartData($customer_no);
}

if(!empty($_POST['confirm']))
{
  header('Location: ' . Bootstrap::ENTRY_URL. 'cart_confirm.php');

}

//crt_idが設定されていれば、削除する
if($crt_id !== '') {
  $res = $cart->delCartData($crt_id);
}

//カート情報を取得する
$dataArr = $cart->getCartData($customer_no);
//アイテム数と合計金額を取得する。listは配列をそれぞれの変数にわける
list($sumNum, $sumPrice) = $cart->getItemAndSumPrice($customer_no);
$_SESSION['cartdata'] = $dataArr;
$_SESSION['sumnum']= $sumNum;
$_SESSION['sumprice']= $sumPrice;

$context = [];
$context['sumNum'] = $sumNum;
$context['sumPrice'] = $sumPrice;
$context['dataArr'] = $dataArr;
$template = $twig->loadTemplate('cart.html.twig');
$template->display($context);

?>