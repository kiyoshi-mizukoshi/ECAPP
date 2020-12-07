<?php 

namespace shopping\lib;

class Item
{
  public $cateArr = [];
  public $db = null;

  public function __construct($db)
  {
    $this->db = $db;
  }
  public function getSearchList($search){//検索フォーム
    $table = ' item ';
    $col = ' item_id, item_name, price,image, ctg_id ';
    $where = 'item_name like '."'%".$search."%'" . ' or detail like '."'%".$search."%'";
    $res = $this->db->select($table, $col,$where);
    return $res;
  }

  //カテゴリーリストの取得
  public function getCategoryList()
  {
    $table = ' category ';
    $col = ' ctg_id, category_name ';
    $res = $this->db->select($table, $col);//select文をresにいれてる
    return $res;
  }

  //商品リストを取得する
  public function getItemList($ctg_id,$page)
  {
    $pages=($page - 1)*12;
    //カテゴリーによって表示させるアイテムをかえる
    $table = ' item ';
    $col = ' item_id, item_name, price,image, ctg_id ';
    $where = ($ctg_id !== '') ? ' ctg_id = ? ' : 'item_id '  .'LIMIT 12 OFFSET '.$pages.'';
    $arrVal = ($ctg_id !== '') ? [$ctg_id] : [];

    $res = $this->db->select($table, $col,$where,$arrVal);

    return ($res !== false && count($res) !== 0) ? $res : false; 
  }

  //商品の詳細情報を取得する
  public function getItemDetailData($item_id)
  {
    $table = ' item ';
    $col = ' item_id, item_name, detail, price, image, ctg_id ';

    $where = ($item_id !== '') ? ' item_id = ? ' : '';
    //カテゴリーによって表示させるアイテムをかえる
    $arrVal = ($item_id !== '') ? [$item_id] : [];
    $res = $this->db->select($table, $col, $where, $arrVal);

    return ($res !== false && count($res) !== 0) ? $res : false;
  }

  public function countItem($ctg_id)
  {
    $table = ' item ';
    $col = ' count(item_id) as count ';

    $where =($ctg_id !== '') ? ' ctg_id = ? ' : ' item_id ';
    //カテゴリーによって表示させるアイテムをかえる
    $arrVal = [$ctg_id];
    $res = $this->db->select($table, $col, $where, $arrVal);
    return ($res !== false && count($res) !== 0) ? $res : false;

  }
}

?>