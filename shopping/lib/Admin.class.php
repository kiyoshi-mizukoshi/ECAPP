<?php 

namespace shopping\lib;

class Admin
{
  private $db = null;

  public function __construct($db = null)
  {
    $this->db = $db;
  }

public function checkDate($aggregate)
{if(preg_match('/^\d{4}$/' , $aggregate['year'])===0 ||preg_match('/^\d{4}$/' , $aggregate['secondYear'])===0 || preg_match('/^\d{1,2}$/' , $aggregate['month'])===0 || preg_match('/^\d{1,2}$/' , $aggregate['secondMonth'])===0
|| preg_match('/^\d{1,2}$/' , $aggregate['secondMonth'])===0 || preg_match('/^\d{1,2}$/' , $aggregate['day'])===0 || preg_match('/^\d{1,2}$/' , $aggregate['secondDay'])===0 || checkdate($aggregate['month'],$aggregate['day'],$aggregate['year'] === false) || checkdate($aggregate['secondMonth'],$aggregate['secondDay'],$aggregate['secondYear'] ===false)){
  return $this->err = '<span class="err">無効な年、もしくは数字以外が入力されています</span>';
  exit();
}else{
  return true;
}
}

public function selectData($aggregate)
{  
  
    $firstAgg=[];
    $secondAgg=[];
    array_push($firstAgg,$aggregate['year'],$aggregate['month'],$aggregate['day']);
    array_push($secondAgg,$aggregate['secondYear'],$aggregate['secondMonth'],$aggregate['secondDay']);
    $firstAgg=implode('-',$firstAgg);
    $secondAgg=implode('-',$secondAgg);  

  $table = ' history ';
  $column = ' DATE(regist_date) as date, sum(price) as price';
  $where = 'regist_date BETWEEN '. "'".$firstAgg."'". ' AND '. "'".$secondAgg. " 23:59:59 '".' group by date';
  $arrVal = [$firstAgg, $secondAgg];

  return $this->db->select($table, $column, $where, $arrVal);
//SELECT DATE(regist_date) as date, sum(price) as price FROM history WHERE regist_date BETWEEN '2018-01-01' AND '2020-11-30 23:59:59' GROUP BY date

}

public function selectAdmin()
{
  $table = ' member';
  $column = ' mem_id, family_name, first_name, family_name_kana, first_name_kana, email, regist_date, delete_flg';
  $where = 'delete_flg = 0 ';
  $arrVal = [];
  return $this->db->select($table, $column, $where, $arrVal);

}

public function selectAllAdmin($mem_id)
{
  $table = ' member';
  $column = ' mem_id, family_name, first_name, family_name_kana, first_name_kana, year, month, day, zip1, zip2, address, address2, email, tel1, tel2, tel3, regist_date';
  $where = 'mem_id=?';
  $arrVal = [$mem_id];
  return $this->db->select($table, $column, $where, $arrVal);

}

public function selectProducts()
{
  $table = ' item i LEFT JOIN category c ON i.ctg_id = c.ctg_id ';
  $column = ' i.item_id, i.item_name,i.detail,i.price,i.image,i.ctg_id,c.ctg_id, category_name ';
  $where = '';
  $arrVal = [];
  return $this->db->select($table, $column, $where, $arrVal);
//SELECT i.item_id, i.item_name,i.detail,i.price,i.image,i.ctg_id,c.ctg_id,category_name FROM item i LEFT JOIN category c ON i.ctg_id = c.ctg_id
}

public function insertProducts($detail,$img)
{
  $table = ' item ';
  $insData = ['item_name'=>$detail['item_name'],
              'detail'=>$detail['detail'],
              'price'=>$detail['price'],
              'image'=>$img,
              'ctg_id'=>$detail['ctg_id']];
  return $this->db->insert($table, $insData);

}

public function categoryAdd($newcategory)
{
  $table = ' category ';
  $insData = ['category_name'=>$newcategory['category_name']];
  return $this->db->insert($table, $insData);

}

public function productsData($item_id)
{
  $table = ' item ';
  $column = ' * ';
  $where = ' item_id = ? ';
  $arrVal = [$item_id];
  return $this->db->select($table, $column, $where, $arrVal);
}

public function productsCategory($ctg_id)
{
  $table = ' category ';
  $col = ' ctg_id, category_name ';
  $where = 'ctg_id = ?';
  $arrVal = [$ctg_id];
  return $this->db->select($table, $col, $where, $arrVal);
}

public function updateProducts($dataArr,$id)
{
  unset($dataArr['submit']);
  $table = ' item ';
  $insData = $dataArr;
  $where = ' item_id = ? ';
  $arrWhereVal =  [$id];

  return $this->db->update($table, $insData, $where, $arrWhereVal);
}

public function removeProducts($id)
{
  //DELETE FROM `item` WHERE `item`.`item_id` = 22
  $table =' item ';
  $where = ' item_id = ? ';
  $arrval = [$id];
  return $this->db->delete($table, $where, $arrval);

}

public function updateImage($id,$img)
{
  $table = ' item ';
  $insData = ['image' => $img];
  $where = ' item_id = ? ';
  $arrWhereVal =  [$id];

  return $this->db->update($table, $insData, $where, $arrWhereVal);
}

public function delRegist($mem_id)
{
  $table = ' member ';
  $insData = ['delete_date'=>date("Y-m-d H:i:s"),'delete_flg' => 1];
  $where = ' mem_id = ? ';
  $arrWhereVal = [$mem_id];
  
  return $this->db->update($table, $insData, $where, $arrWhereVal);  


}

public function adminLogin($dataArr)
{

  $table = ' admin';
  $col = ' * ';
  $where = 'email=' ."'".$dataArr['email'] ."'". ' AND password= '. "'".$dataArr['password']."'";
  $res = $this->db->select($table, $col,$where);
  return $res;

}

}
?>