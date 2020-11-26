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

//セッションに、セッションIDを設定する
//$ses->checkSession();いれなくてもいい
//ログインされていなければ、ログイン画面に飛ばす
if(isset($_SESSION['id'])=='')
{
  header('Location: ' . Bootstrap::ENTRY_URL. 'login_form.php');

}
$customer_no = $_SESSION['id'];

$dataArr = $mypage->historyData($customer_no);
$context = [];
$context['dataArr'] = $dataArr;
$template = $twig->loadTemplate('history.html.twig');
$template->display($context);

?>