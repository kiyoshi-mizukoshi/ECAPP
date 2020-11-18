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
    $where = 'email=' ."'".$dataArr['email'] ."'". ' AND password= '. "'".$dataArr['password']."'";
    $res = $this->db->select($table, $col,$where);
    return $res;

  }


}
?>
