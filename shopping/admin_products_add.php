<?php 
namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\Bootstrap;
use shopping\lib\PDODatabase;
use shopping\lib\Session;
use shopping\lib\Admin;
use shopping\lib\Item;


$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, ['cache' => Bootstrap::CACHE_DIR]);

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
$admin = new Admin($db);
$itm = new Item($db);
$cateArr = $itm->getCategoryList();

$msg='';
$detail='';
$detail=$_POST;
if(isset($_POST['submit']) ===true){
  $tmp_image = $_FILES['image'];
  if(preg_match('/^\d+$/' , $detail['price']) === 0)
  {
    $msg='価格は数字で入力してください';
    
  }
  //var_dump($tmp_image);
  //エラーがなく、サイズが０ではないか
  if($tmp_image['error'] === 0 && $tmp_image['size'] !== 0) {
    // 正しくサーバにアップされているかどうか
    if(is_uploaded_file($tmp_image['tmp_name'])=== true){
      //画像情報を取得する
      $image_info = getimagesize($tmp_image['tmp_name']);
      $image_mime = $image_info['mime'];
      //画像サイズが利用できるサイズいないかどうか
      if($tmp_image['size'] > 1048576){
        $msg= 'アップロードできる画像のサイズは、1MBまでです';
        //画像の形式が利用できるタイプかどうか
      }elseif(preg_match('/^image\/jpeg$/', $image_mime)===0){
        $msg= 'アップロードできる画像の形式は、JPEG形式です';
        //time:現在時刻をUnixエポック（1970年１月1日00:00:00GMT)からの通算秒として返す（Unixタイムスタンプ）
      }elseif(move_uploaded_file($tmp_image['tmp_name'], './images/upload_' . time() . '.jpg')===true){
        $img = 'upload_' . time() . '.jpg';
        $res = $admin->insertProducts($detail,$img);
        if($res===true)
        {
          $msg='商品登録が完了しました';
        }else{
          $msg='商品登録に失敗しました';
        }
      }
    }
  }
}

$context = [];
$context['cateArr'] = $cateArr;
$context['msg'] = $msg;
$template = $twig->loadTemplate('admin_products_add.html.twig');
$template->display($context);



