<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\lib\PDODatabase;
use shopping\Bootstrap;
use shopping\lib\Member;
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$db_host = $_ENV['DB_HOST'];
$db_name = $_ENV['DB_NAME'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS'];
$db_type = $_ENV['DB_TYPE'];



$db = new PDODatabase($db_host,$db_user,$db_pass,$db_name,$db_type);
$member = new Member($db);
if(isset($_GET['zip1'])===true && isset($_GET['zip2'])===true) {
  $zip1 = $_GET['zip1'];
  $zip2 = $_GET['zip2'];
  $res = $member->getaddress($zip1,$zip2);
  //出力結果がajaxに渡される
  echo ($res !== "" && count($res) !== 0) ? $res[0]['pref'] 
  . $res[0]['city'] . $res[0]['town'] :'';
}else {
  echo "no";
}


