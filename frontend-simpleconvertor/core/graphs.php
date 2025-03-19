<?php
 
 switch ($action)
 {
  default:
   
  $periodType                                   = "monthly";  // we would like data monthly
  
  $listFarmsRainfall  = array (array ());;    // double list which will receive the value of rainfall
 

 
  $arrMonthsSensor    = array();
  $year_start         = "2021-01";       // we want the graph from the start of last year


  for ($i=0;$i<11;$i++) {
    $arrMonthsSensor[$i] = date('Y-m', strtotime(' +' . $i. 'month', strtotime($year_start)));
  }
  

  $test_data_label1   =$arrMonthsSensor;  
  
  $farmsSensorMonthlyValue =array (array ());

  $listFarmsRainfall  = CallAPI("GET", "",$global['api_url']."farmsStats/"."read/");
  


  $j=0;  
  for($i=1; $i<count($listFarmsRainfall->farms_stats); $i++) {      
           
     $famrId   = $listFarmsRainfall->farms_stats[$i]->farms_id;
     $average  = $listFarmsRainfall->farms_stats[$i]->average;
             
     $farmsSensorMonthlyValue[$famrId-1][$j]=trim($average);  
     $j++;                 
     if ($i<count($listFarmsRainfall->farms_stats)-1) {
        if ($famrId!==$listFarmsRainfall->farms_stats[$i+1]->farms_id) {
          $j=0;
         }
     }
   } 
  
 
  $testDataSerie            =  $farmsSensorMonthlyValue;  
  $testDataLow              =  0.00;  //origin  


  $link_farms_Friman        ="#";
  $link_farms_PartialTech   ="#";
  $link_farms_Noora         ="#";
  $link_farms_Organic       ="#";

  //echo $link_farms;
  $listAllFarms             = Array();

  $listAllFarms             = CallAPI("GET", "",$global['api_url']."/v1/farms");  // get all farms list


 }
?>
