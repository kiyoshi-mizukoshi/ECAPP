<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\PDODatabase;
use shopping\lib\Session;
use shopping\lib\Item;

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

$DB_HOST = $_ENV["DB_HOST"];
$DB_DATABASE = $_ENV["DB_DATABASE"];
$DB_USERNAME = $_ENV["DB_USERNAME"];
$DB_PASSWORD = $_ENV["DB_PASSWORD"];
$db_type = $_ENV["DB_TYPE"];

$db = new PDODatabase(
    $DB_HOST,
    $DB_USERNAME,
    $DB_PASSWORD,
    $DB_DATABASE,
    $db_type
);
$ses = new Session($db);
$itm = new Item($db);

//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, ['cache' =>Bootstrap::CACHE_DIR]);

if(isset($_SESSION['name'])){
  $username = $_SESSION['name'];

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
$ses->checkSession();
$ctg_id = (isset($_GET[('ctg_id')]) === true && preg_match('/^[0-9]+$/' , $_GET['ctg_id'])===1) ? $_GET['ctg_id'] : '';
//カテゴリーリスト（一覧）を取得する
$cateArr = $itm->getCategoryList();
$_SESSION['cateArr'] = $cateArr;
//商品リストを取得する
$dataArr = $itm->getItemList($ctg_id);
$context = [];
$context['cateArr'] = $cateArr;
$context['dataArr'] = $dataArr;
$context['name'] = $name;
$context['gest'] = $gest;
$context['link'] = $link;
$context['regist'] = $regist;

$template = $twig->loadTemplate('list.html.twig');
$template->display($context); 


?>