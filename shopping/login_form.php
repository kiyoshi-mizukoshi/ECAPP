<?php 
namespace shopping;
require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\lib\PDODatabase;
use shopping\Bootstrap;
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
//テンプレート設定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,['cache' => Bootstrap::CACHE_DIR]);




$context=[];
$template = $twig->loadTemplate('login_form.html.twig');
$template->display($context); 


?>