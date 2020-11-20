<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\PDODatabase;
use shopping\lib\Session;
use shopping\lib\Login;
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$db_host = $_ENV['DB_HOST'];
$db_name = $_ENV['DB_NAME'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS'];
$db_type = $_ENV['DB_TYPE'];


$db = new PDODatabase($db_host,$db_user,$db_pass,$db_name,$db_type);
$ses = new Session($db);
$login=new Login($db);

//テンプレート指定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, ['cache' =>Bootstrap::CACHE_DIR]);

$gestLogin = $login->gestLogin();
if(!empty($gestLogin))
{
  $_SESSION['id'] = $gestLogin[0]['mem_id'];
  $_SESSION['name'] = $gestLogin[0]['family_name'].$gestLogin[0]['first_name'];
  header('Location: ' . Bootstrap::ENTRY_URL. 'list.php');
}



$context = [];
$template = $twig->loadTemplate('list.html.twig');
$template->display($context); 


?>