<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\lib\PDODatabase;
use shopping\lib\Session;
use shopping\Bootstrap;
use shopping\lib\Contact;
if (file_exists(__DIR__ . '/.env')) {
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
//テンプレート設定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,['cache' => Bootstrap::CACHE_DIR]);
$contact = new Contact($db);
$ses = new Session($db);

$dataArr=$_SESSION['join'];
var_dump($_SESSION['join']);
if(!empty($_POST)){
  $res= $contact->insertContact($dataArr);
  header('Location: ' . Bootstrap::ENTRY_URL. 'contact_complete.php');


}

$context = [];
$context['dataArr'] = $dataArr;
$template = $twig->loadTemplate('contact_confirm.html.twig');
$template->display($context);
?>
