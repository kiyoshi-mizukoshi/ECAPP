<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\PDODatabase;
use shopping\lib\Session;
use shopping\lib\Mypage;
use shopping\lib\initMaster;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$db_host = $_ENV['DB_HOST'];
$db_name = $_ENV['DB_NAME'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS'];
$db_type = $_ENV['DB_TYPE'];


$db = new PDODatabase($db_host,$db_user,$db_pass,$db_name,$db_type);
$ses = new Session($db);
$mypage = new Mypage($db);
//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, ['cache' =>Bootstrap::CACHE_DIR]);

if(isset($_SESSION['id'])=='')
{
  header('Location: ' . Bootstrap::ENTRY_URL. 'login_form.php');

}

$regist = $_SESSION['regist'];
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
$context['regist'] = $regist;
$context['yearArr'] = $yearArr;
$context['monthArr'] = $monthArr;
$context['dayArr'] = $dayArr;
$context['errArr'] = $errArr;

$template = $twig->loadTemplate('update.html.twig');
$template->display($context); 


?>