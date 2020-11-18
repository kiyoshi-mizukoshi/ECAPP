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

//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, ['cache' =>Bootstrap::CACHE_DIR]);

//SessionKeyを見て、DBへの登録状態をチェックする
if(isset($_SESSION['name'])){
  $username = $_SESSION['name'];

}
if (isset($_SESSION['id'])) {//ログインしているとき
    $msg = 'こんにちは' . $username . 'さん';
    $link = '<a href="logout.php">ログアウト</a>';
} else {//ログインしていない時
    $msg = 'ログインしていません';
    $link = '<a href="login_form.php">ログイン</a>';
}
$ses->checkSession();
$ctg_id = (isset($_GET[('ctg_id')]) === true && preg_match('/^[0-9]+$/' , $_GET['ctg_id'])===1) ? $_GET['ctg_id'] : '';

//カテゴリーリスト（一覧）を取得する
$cateArr = $itm->getCategoryList();
//商品リストを取得する
$dataArr = $itm->getItemList($ctg_id);
$context = [];
$context['cateArr'] = $cateArr;
$context['dataArr'] = $dataArr;
$context['msg'] = $msg;
$context['link'] = $link;
$template = $twig->loadTemplate('list.html.twig');
$template->display($context); 


?>