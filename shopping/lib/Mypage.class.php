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

  public function updateRegist($dataArr,$id)
  {
    $dataArr +=['update_date'=>date("Y-m-d H:i:s")];
    $table = ' member ';
    $insData = $dataArr;
    $where = ' mem_id = ? ';
    $arrWhereVal =  [$id];
    
    return $this->db->update($table, $insData, $where, $arrWhereVal);  

//[SQL_LOG:2020-11-19 19:46:44]:  UPDATE  member  SET family_name =? ,first_name =? ,family_name_kana =? ,first_name_kana =? ,year =? ,month =? ,day =? ,zip1 =? ,zip2 =? ,address =? ,email =? ,tel1 =? ,tel2 =? ,tel3 =? ,password =?  WHERE  mem_id = ?  [水越,聖士,みずこし,きよし,1994,09,28,121,0822,東京都足立区西竹の塚2-2-7,928kiyoshi@gmail.com,090,1261,6760,a642a77abd7d4f51bf9226ceaf891fcbb5b299b8,水越,聖士,みずこし,きよし,1994,09,28,121,0822,東京都足立区西竹の塚2-2-7,928kiyoshi@gmail.com,090,1261,6760,a642a77abd7d4f51bf9226ceaf891fcbb5b299b8]

  }


  public function errorCheck($join)
  {
    $this->join = $join;
    //クラス内のメソッドを読み込む
    $this->createErrorMessage();
    $this->familyNameCheck();
    $this->firstNameCheck();
    $this->birthCheck();
    $this->zipCheck();
    $this->addCheck();
    $this->telCheck();
    $this->mailCheck();

    return $this->errArr;

  }

  private function createErrorMessage()
  {
    foreach ($this->join as $key => $val){
      $this->errArr[$key] = '';
    }
  }

  private function familyNameCheck()
  {
    if($this->join['family_name']===''){
      $this->errArr['family_name'] = 'お名前（氏）を入力してください';
    }
  }

  private function firstNameCheck()
  {
    //エラーチェックを入れる
    if($this->join['first_name']===''){
      $this->errArr['first_name'] = 'お名前（名）を入力してください';
    }
  }


  private function birthCheck()
  {
    if($this->join['year']===''){
      $this->errArr['year'] = '生年月日の年を選択してください';
    }
    if($this->join['month']===''){
      $this->errArr['month'] = '生年月日の月を選択してください';
    }
    if($this->join['day']===''){
      $this->errArr['day'] = '生年月日の日を選択してください';
    }

    if(checkdate($this->join['month'], $this->join['day'],$this->join['year'])===false) {
      $this->errArr['year'] = '正しい日付を入力してください';
    }

    if(strtotime($this->join['year'] . '-' . $this->join['month'] . '-' . $this->join['day']) - strtotime('now')>0){
      $this->errArr['year'] = '正しい日付を入力してください';
    }
  }

  private function zipCheck()
  {
    if(preg_match('/^[0-9]{3}$/' , $this->join['zip1'])===0) {
      $this->errArr['zip1'] = '郵便番号の上は半角数字３桁で入力してください';
    }

    if(preg_match('/^[0-9]{4}$/' , $this->join['zip2'])===0){
      $this->errArr['zip2'] = '郵便番号の下は半角数字４桁で入力してください';
    }
  }

  private function addCheck()
  {
    if($this->join['address']===''){
      $this->errArr['address'] = '住所を入力してください';
    }
  }

  

  private function mailCheck()
  {
    if(preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+[a-zA-Z0-9\._-]+$/' , $this->join['email'])===0){
      $this->errArr['email'] = 'メールアドレスを正しい形式で入力してください';
    }
    }

  

  private function telCheck()
  {
    if(preg_match('/^\d{1,6}$/' , $this->join['tel1'])===0 ||
        preg_match('/^\d{1,6}$/' , $this->join['tel2'])===0||
        preg_match('/^\d{1,6}$/' , $this->join['tel3'])===0||
        strlen($this->join['tel1'] . $this->join['tel2']  . $this->join['tel3'])>=12) {
          $this->errArr['tel1']='電話番号は、半角数字で１１桁以内で入力してください';
        }
  }





  public function getErrorFlg()
  {
    $err_check = true;
    foreach ($this->errArr as $key =>$value){
      if($value !==''){
        $err_check = false;
      }
    }
    return $err_check;

  }







}



?>