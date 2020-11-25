<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\lib\initMaster;
use shopping\lib\PDODatabase;
use shopping\lib\Common;
use shopping\lib\Member;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

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

$common = new Common($db);
$member = new Member($db);

//モード判定（どの画面から来たかの判断）
//登録画面から来た場合
if(empty($_POST)){
  $mode = 'regist';
}
if(isset($_POST['confirm'])===true) {
  $mode = 'confirm';
}
//戻る場合
if(isset($_POST['back'])===true){
  $mode = 'back';
}
//登録完了
if(isset($_POST['complete'])===true){
  $mode = 'complete';
}
//ボタンのモードよって処理を変える
switch ($mode) {

  case 'regist':
    header('Location: ' . Bootstrap::ENTRY_URL. 'regist.php');

  break;
  case 'confirm'://新規登録
                 //データを受け継ぐ
                 //↓この情報は入力には必要ない
        unset($_POST['confirm']);

        $dataArr = $_POST;

        //エラーメッセージの配列作成
        $errArr = $common->errorCheck($dataArr);
        $err_check = $common->getErrorFlg();
        //err_check = false → エラーがありますよ！
        //err_check = true → エラーがないですよ！
        //エラーなければconfirm tpl あるとregist.tpl
        $template = ($err_check === true) ? 'confirm.html.twig':'regist.html.twig';

          break;

        case 'back': //戻って来た時
                    //ポストされたデータを元に戻すので、$dataArrにいれる
          $dataArr = $_POST;

          unset($dataArr['back']);

          //エラーも定義しておかないと、Undefinedエラーがでる
          foreach($dataArr as $key => $value){
            $errArr[$key] = '';
          }

          $template = 'regist.html.twig';
          break;

        case 'complete' : //登録完了
          $dataArr = $_POST;
          $dataArr['password']=sha1($dataArr['password']);//パスワードのハッシュ化

          //↓この情報はいらないので外しておく
          unset($dataArr['complete']);
                  $res= $member->insertData($dataArr);
                    //登録成功時は完成ページへ
                    if($res===true){
                    header('Location:' . Bootstrap::ENTRY_URL. 'complete.php');//ページ遷移する関数
                    exit();
                  }else {
                    //登録失敗時は登録画面に戻る
                    $template = 'regist.html.twig';

                    foreach ($dataArr as $key => $value){
                      $errArr[$key] = '';
                    }
                  }

                break;

}




list($yearArr,$monthArr,$dayArr) = initMaster::getDate();

$context['yearArr'] = $yearArr;
$context['monthArr'] = $monthArr;
$context['dayArr'] = $dayArr;

$context['dataArr'] = $dataArr;
$context['errArr'] = $errArr;
$template = $twig->loadTemplate($template);
$template->display($context);
?>
