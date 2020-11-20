<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\Session;
use shopping\lib\PDODatabase;
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$db_host = $_ENV['DB_HOST'];
$db_name = $_ENV['DB_NAME'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS'];
$db_type = $_ENV['DB_TYPE'];
$db = new PDODatabase($db_host,$db_user,$db_pass,$db_name,$db_type);


$ses = new Session($db);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[ 'cache' =>Bootstrap::CACHE_DIR]);
$msg='';
if(!empty($_POST))
if(strlen($_POST['password1'])<8){
$msg='パスワードは8文字以上で入力してください';
}elseif($_POST['password1']!==$_POST['password2']){
$msg='パスワードが確認用と一致しません';
}
else
{
  $_SESSION['password']=$_POST;
  header('Location: ' . Bootstrap::ENTRY_URL. 'password_confirm.php');

}
$context = [];
$context['msg']=$msg;
$template = $twig->loadTemplate('password.html.twig');
$template->display($context);




?>