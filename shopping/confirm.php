<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\lib\initMaster;
use shopping\lib\PDODatabase;
use shopping\lib\Common;

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$db_host = $_ENV['DB_HOST'];
$db_name = $_ENV['DB_NAME'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS'];
$db_type = $_ENV['DB_TYPE'];
$db = new PDODatabase($db_host,$db_user,$db_pass,$db_name,$db_type);
global $db;

//テンプレート設定
$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader,['cache' => Bootstrap::CACHE_DIR]);

$common = new Common($db);
//モード判定（どの画面から来たかの判断）
//登録画面から来た場合
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

          //↓この情報はいらないので外しておく
          unset($dataArr['complete']);
          $column = '';
          $insData = '';


          //foreachのなかでSQL文を作る
          foreach($dataArr as $key => $value){
            $column .= $key . ',';
            $insData .= $value . ',';

            
          }

          $query = "INSERT INTO member ("
                  . $column
                  . " regist_date"
                  . ") VALUES("
                  . $insData
                  ." NOW() "
                  .")";

                  $res= $db->query($query);
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

