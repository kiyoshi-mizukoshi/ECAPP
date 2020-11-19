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