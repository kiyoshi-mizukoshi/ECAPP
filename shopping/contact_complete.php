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
$header = $_ENV['HEADERS'];
$db = new PDODatabase($db_host,$db_user,$db_pass,$db_name,$db_type);

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, ['cache' => Bootstrap::CACHE_DIR]);
$ses = new Session($db);

mb_language("Japanese");
mb_internal_encoding("UTF-8");
$to = $_SESSION['contact']['mail'];
$title = $_SESSION['contact']['name'];
$message = $_SESSION['contact']['inquiry'];
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



