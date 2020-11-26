<?php 
namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\PDODatabase;
use shopping\lib\Session;
use shopping\lib\Login;

if (file_exists(__DIR__ . '/../.env')) {
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
$ses = new Session($db);
$login=new Login($db);

//テンプレート設定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,['cache' => Bootstrap::CACHE_DIR]);


$dataArr = $_POST;
$dataArr['password']=sha1($dataArr['password']);//パスワードのハッシュ化

$res= $login->selectData($dataArr);
//登録成功時は完成ページへ
if(!empty($res)){
  $msg = 'ログインしました。';
  $_SESSION['id'] = $res[0]['mem_id'];
    $_SESSION['name'] = $res[0]['family_name'].$res[0]['first_name'];
  $link = '<a href="list.php">ホーム</a>';
}else {
//登録失敗時は登録画面に戻る
$msg = 'メールアドレスもしくはパスワードが間違っています。';
$link = '<a href="login_form.php">戻る</a>';
}





$context=[];
$context['msg']=$msg;
$context['link']=$link;
$template = $twig->loadTemplate('login.html.twig');
$template->display($context);


?>