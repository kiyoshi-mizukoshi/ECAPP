<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\Session;
use shopping\lib\PDODatabase;
use shopping\lib\Mypage;
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

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,[ 'cache' =>Bootstrap::CACHE_DIR]);
$password=[];

if(!empty($_POST))
{
  $password['password']=sha1($_SESSION['password']['password1']);
  var_dump($password);
  $id=$_SESSION['id'];
  $update=$mypage->UpdatePassword($password , $id);
  header('Location: ' . Bootstrap::ENTRY_URL. 'password_complete.php');

}
$context = [];
$template = $twig->loadTemplate('password_confirm.html.twig');
$template->display($context);




?>