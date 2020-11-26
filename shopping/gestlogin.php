<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\PDODatabase;
use shopping\lib\Session;
use shopping\lib\Login;
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