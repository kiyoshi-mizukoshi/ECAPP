<?php 

namespace shopping\lib;

class initMaster
{

  public static function getDate()
  {
    $yearArr = [];
    $monthArr = [];
    $dayArr = [];

    $next_year = date('Y') + 1;
    //現在の年を取得＋１＝来年

    //年を作成
    for($i = 1920; $i < $next_year; $i++){
      $year = sprintf("%04d",$i);
      $yearArr[$year] = $year . '年';
    }

    //月を作成
    for($i =1; $i <13; $i++){
      $month = sprintf("%02d" , $i);
      $monthArr[$month] = $month . '月'; 
    }

    //日を作成
    for($i = 1; $i<32; $i++){
      $day = sprintf("%02d" , $i);
      $dayArr[$day] = $day . '日';
    }
    return [$yearArr , $monthArr, $dayArr];
  }


  
}




