<?php 
namespace shopping\lib;

class Contact
{
  private $db = null;

  public function __construct($db = null)
  {
    $this->db = $db;
  }

  public function insertContact($dataArr)
  {
    $table = ' contact ';
    $dataArr['regist_date']= date("Y-m-d H:i:s") ;
    return $this->db->insert($table, $dataArr);
  }

  
}



?>