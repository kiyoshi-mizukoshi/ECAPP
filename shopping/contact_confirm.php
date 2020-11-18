<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\lib\PDODatabase;
use shopping\lib\Session;
use shopping\Bootstrap;
use shopping\lib\Contact;
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$db_host = $_ENV['DB_HOST'];
$db_name = $_ENV['DB_NAME'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS'];
$db_type = $_ENV['DB_TYPE'];
$db = new PDODatabase($db_host,$db_user,$db_pass,$db_name,$db_type);
//テンプレート設定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,['cache' => Bootstrap::CACHE_DIR]);
$contact = new Contact($db);
$ses = new Session($db);

$dataArr = $_POST;
$_SESSION['contact']=$_POST;

$res= $contact->insertContact($dataArr);

$context = [];
$context['dataArr'] = $dataArr;
$template = $twig->loadTemplate('contact_confirm.html.twig');
$template->display($context);
?>
