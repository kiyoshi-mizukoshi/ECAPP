<?php 
namespace shopping\lib;

class Member
{
  private $db = null;

  public function __construct($db = null)
  {
    $this->db = $db;
  }

  public function insertData($dataArr)
  {
    $table = ' member ';
    $dataArr['regist_date']= date("Y-m-d H:i:s") ;
    var_dump($dataArr);
    return $this->db->insert($table, $dataArr);
  }

  public function getaddress($zip1,$zip2){
    $zip = $zip1 . $zip2;
    $table = ' postcode ';
    $col = ' pref, city, town ';
    $where = 'zip='.$zip . ' LIMIT 1 ';
    $res = $this->db->select($table, $col,$where);
    return $res;
  }
}



?>