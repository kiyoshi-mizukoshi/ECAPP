<?php 
namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\Session;
use shopping\lib\PDODatabase;
if (file_exists(__DIR__ . '/.env')) {
  $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
  $dotenv->load();
}

$DB_HOST = $_ENV["DB_HOST"];
$DB_DATABASE = $_ENV["DB_DATABASE"];
$DB_USERNAME = $_ENV["DB_USERNAME"];
$DB_PASSWORD = $_ENV["DB_PASSWORD"];
$db_type = $_ENV["DB_TYPE"];
$header = $_ENV["HEADERS"];
$db = new PDODatabase(
    $DB_HOST,
    $DB_USERNAME,
    $DB_PASSWORD,
    $DB_DATABASE,
    $db_type
);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, ['cache' => Bootstrap::CACHE_DIR]);
$ses = new Session($db);

mb_language("Japanese");
mb_internal_encoding("UTF-8");
$to = $_SESSION['join']['mail'];
$title = $_SESSION['join']['name'];
$message = $_SESSION['join']['inquiry'];
$headers = "From: $header ";

  if(mb_send_mail($to, $title, $message,$headers))
  {
    $msg= "メール送信成功です";
    $sentence = "お問い合わせありがとうございます。1営業日以内にご返信させていただきます。<br>
    自動返信メールをお送りしておりますのでご確認ください。<br>
    1時間たっても届かない場合はお手数ですがこちらからご連絡ください。
    ";
  }
  else
  {
    $msg= "メール送信失敗です";
    $sentence = "お問い合わせに失敗しました。";

  }
  $context=[];
  $context['msg'] = $msg;
  $context['sentence'] = $sentence;
  $template = $twig->loadTemplate('contact_complete.html.twig');
  $template->display($context);



