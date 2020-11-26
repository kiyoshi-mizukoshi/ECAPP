<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\PDODatabase;
use shopping\lib\Session;
use shopping\lib\Mypage;

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
$mypage = new Mypage($db);
//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, ['cache' =>Bootstrap::CACHE_DIR]);

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



$dataArr=$_SESSION['confirm'];
$id=$_SESSION['id'];
if(!empty($_POST['complete'])){
  $update= $mypage->updateRegist($dataArr,$id);
  header('Location: ' . Bootstrap::ENTRY_URL. 'update_complete.php');

}



$context = [];
$context['dataArr'] = $dataArr;
$template = $twig->loadTemplate('update_confirm.html.twig');
$template->display($context); 


?>