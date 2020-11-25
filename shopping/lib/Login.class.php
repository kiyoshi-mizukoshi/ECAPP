<?php  
namespace shopping\lib;

class Login
{

  public $db = null;
  
  
  public function __construct($db = null)
  {
    $this->db = $db;
  }

  public function selectData($dataArr)
  {
    $table = ' member';
    $col = ' * ';
    $where = 'email=' ."'".$dataArr['email'] ."'". ' AND password= '. "'".$dataArr['password']."' and " . 'delete_flg= 0 ';
    $res = $this->db->select($table, $col,$where);
    return $res;

  }

  public function gestLogin()
  {
    $table = ' member';
    $col = ' * ';
    $where = 'mem_id= 22';
    $res = $this->db->select($table, $col,$where);
    return $res;

  }

}
?>
