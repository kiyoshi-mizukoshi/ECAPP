<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\PDODatabase;
use shopping\lib\Session;
use shopping\lib\Item;
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
$itm = new Item($db);
$favorite = new Favorite($db);
//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, ['cache' =>Bootstrap::CACHE_DIR]);

if(isset($_SESSION['name'])){
  $username = $_SESSION['name'];

}

//セッションに、セッションIDを設定する
$ses->checkSession();
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

$msg='';
// item_idを取得する
$item_id = (isset($_GET['item_id']) === true && preg_match('/^\d+$/' , $_GET['item_id']) === 1) ? $_GET['item_id'] : '';
$customer_no = (isset($_SESSION['id']) === true && preg_match('/^\d+$/' , $_SESSION['id']) === 1) ? $_SESSION['id'] : '';

//item_idが取得できていない場合、商品一覧へ遷移させる
if($item_id === '') {
  header('Location: ' . Bootstrap::ENTRY_URL. 'list.php');
}

//カテゴリーリスト（一覧）を取得する 
$cateArr = $itm->getCategoryList();

//商品情報を取得する
$itemData = $itm->getItemDetailData($item_id);

//デフォルトで表示されるメッセージ
$fav = $favorite->checkFavorite($customer_no,$item_id);
if(empty($fav))
{
  $msg='<input type="submit" name="favorite" value=お気に入り登録 class="util-link" id="js-submit">';
}else
{
  $msg='<input type="submit" name="favorite" value=お気に入り解除 class="util-link -active" id="js-submit">';
}

//ボタンを押した時の処理
if(!empty($_POST['favorite']) && $customer_no ==="")
{
  header('Location: ' . Bootstrap::ENTRY_URL. 'login_form.php');

}
if(!empty($_POST['favorite']))
{
$count=$favorite->selectFavorite($customer_no, $item_id);
if($count>0)
{  
  $delete = $favorite->deleteFavorite($customer_no, $item_id);
  $msg='<input type="submit" name="favorite" value=お気に入り登録 class="util-link" id="js-submit">';

}else{
  $insert=$favorite->insFavoriteData($customer_no, $item_id);
  $msg='<input type="submit" name="favorite" value=お気に入り解除 class="util-link -active" id="js-submit">';

}
}

$context = [];
$context['cateArr'] = $cateArr;
$context['itemData'] = $itemData[0];
$context['msg'] = $msg;
$context['name'] = $name;
$context['gest'] = $gest;
$context['link'] = $link;
$context['regist'] = $regist;

$template = $twig->loadTemplate('detail.html.twig');
$template->display($context);
?>