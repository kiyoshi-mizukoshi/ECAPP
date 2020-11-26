<?php 

namespace shopping;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use shopping\lib\PDODatabase;
use shopping\Bootstrap;
use shopping\lib\Member;
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


