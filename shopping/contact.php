<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\Session;
use shopping\lib\PDODatabase;
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

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[ 'cache' =>Bootstrap::CACHE_DIR]);

if(!empty($_POST))
{
  $_SESSION['join']=$_POST;
  header('Location: ' . Bootstrap::ENTRY_URL. 'contact_confirm.php');

}
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

$context = [];
$context['name'] = $name;
$context['gest'] = $gest;
$context['link'] = $link;
$context['regist'] = $regist;
$context['cateArr'] = $_SESSION['cateArr'];
$template = $twig->loadTemplate('contact.html.twig');
$template->display($context);




?>