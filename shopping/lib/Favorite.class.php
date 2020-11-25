<?php 
namespace shopping\lib;

class Favorite
{
  private $db = null;

  public function __construct($db = null)
  {
    $this->db = $db;
  }


  public function selectFavorite($customer_no, $item_id)
  {
    $table = ' favorite ';
    $col = ' item_id=' .  $item_id. ' and customer_no=' . $customer_no .' and delete_flg= 0';
    $res = $this->db->count($table, $col);
    return $res;
}

  public function insFavoriteData($customer_no, $item_id)
  {
    $table = ' favorite ';
    $insData = [
      'customer_no' => $customer_no,
      'item_id' =>$item_id
    ];
    return $this->db->insert($table, $insData);
  }

  public function deleteFavorite($customer_no,$item_id)
  {
    $table = ' favorite ';
    $insData = ['delete_flg' => 1];
    $where = ' customer_no=? and item_id = ? ';
    $arrWhereVal = [$customer_no,$item_id];

    return $this->db->update($table, $insData, $where, $arrWhereVal);
  }

  public function getFavoriteData($customer_no)
  {
    $table = ' favorite f LEFT JOIN item i  ON f.item_id = i.item_id ';
    $column = '  f.id as id, i.item_id, i.item_name, i.price, i.image';
    $where = ' f.customer_no = ? AND f.delete_flg = ? ';
    $arrVal = [$customer_no, 0];

    return $this->db->select($table, $column, $where, $arrVal);

  }

  public function checkFavorite($customer_no,$item_id)
  {
    $table = ' favorite ';
    $column = 'customer_no, item_id, delete_flg ';
    $where = ' customer_no=? and item_id=? and delete_flg= ? ';
    $arrVal = [$customer_no, $item_id, 0];
    return $this->db->select($table, $column, $where, $arrVal);

  }



  
}



?>