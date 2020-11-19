<?php 
namespace shopping\lib;

class Mypage
{
  private $db = null;

  public function __construct($db = null)
  {
    $this->db = $db;
  }

  public function getRegist()
  {
    $table = ' member ';
    $col = ' mem_id, family_name, first_name, family_name_kana, first_name_kana, year, month, day, zip1, zip2, address, email, tel1, tel2, tel3, password, regist_date  ';
    $where = 'mem_id=' . "'".$_SESSION['id']."'";
    $res = $this->db->select($table, $col,$where);
    return $res;
  }

  public function delRegist($mem_id)
  {
    $table = ' member ';
    $insData = ['delete_date'=>date("Y-m-d H:i:s"),'delete_flg' => 1];
    $where = ' mem_id = ? ';
    $arrWhereVal = [$mem_id];
    
    return $this->db->update($table, $insData, $where, $arrWhereVal);  


  }






}



?>