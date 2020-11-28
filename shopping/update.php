<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\PDODatabase;
use shopping\lib\Session;
use shopping\lib\Mypage;
use shopping\lib\initMaster;

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


$register = $_SESSION['register'];
list($yearArr,$monthArr,$dayArr) = initMaster::getDate();
$errArr = [
  'family_name' => '',
  'first_name' => '',
  'family_name_kana' => '',
  'first_name_kana' => '',
  'year' => '',
  'month' => '',
  'day' => '',
  'zip1' => '',
  'zip2' => '',
  'address' => '',
  'email' => '',
  'tel1' => '',
  'tel2' => '',
  'tel3' => '',
  
];


if(!empty($_POST))
{
  $_SESSION['join'] = $_POST;
  
  $join = $_SESSION['join'];
  unset($join['confirm']);
var_dump($join);

  $errArr = $mypage->errorCheck($join);
  $err_check = $mypage->getErrorFlg();
  if($err_check === true){
    header('Location:' . Bootstrap::ENTRY_URL. 'update_confirm.php');//ページ遷移する関数
    $_SESSION['confirm']=$join;
  }
}
if(!empty($_GET)){
  if($_GET['action']=='rewrite')
  {
    $regist=[];
    $regist[]=$_SESSION['confirm'];
  }
}



$context = [];
$context['register'] = $register;
$context['yearArr'] = $yearArr;
$context['monthArr'] = $monthArr;
$context['dayArr'] = $dayArr;
$context['errArr'] = $errArr;
$context['name'] = $name;
$context['gest'] = $gest;
$context['link'] = $link;
$context['regist'] = $regist;
$context['cateArr'] = $_SESSION['cateArr'];


$template = $twig->loadTemplate('update.html.twig');
$template->display($context); 


?>