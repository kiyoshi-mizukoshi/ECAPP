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

//セッションに、セッションIDを設定する
$ses->checkSession();
$msg='';
// item_idを取得する
$item_id = (isset($_GET['item_id']) === true && preg_match('/^\d+$/' , $_GET['item_id']) === 1) ? $_GET['item_id'] : '';
$customer_no = $_SESSION['id'];
//item_idが取得できていない場合、商品一覧へ遷移させる
if($item_id === '') {
  header('Location: ' . Bootstrap::ENTRY_URL. 'list.php');
}

//カテゴリーリスト（一覧）を取得する
$cateArr = $itm->getCategoryList();

//商品情報を取得する
$itemData = $itm->getItemDetailData($item_id);

$fav = $favorite->checkFavorite($customer_no,$item_id);
if(empty($fav))
{
  $msg='お気に入り登録';
}else
{
  $msg='お気に入り解除';
}

if(!empty($_POST['favorite']))
{
$count=$favorite->selectFavorite($customer_no, $item_id);
if($count>0)
{  
  $delete = $favorite->deleteFavorite($customer_no, $item_id);
  $msg = 'お気に入り登録';

}else{
  $insert=$favorite->insFavoriteData($customer_no, $item_id);
  $msg = 'お気に入り解除';

}
}

$context = [];
$context['cateArr'] = $cateArr;
$context['itemData'] = $itemData[0];
$context['msg'] = $msg;
$template = $twig->loadTemplate('detail.html.twig');
$template->display($context);
?>