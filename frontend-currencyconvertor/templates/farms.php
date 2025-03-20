<?php 
 $pages   ="farms";     
 
 switch ($global['action'])
 {                       
  case "details":
   $pages .="/farms-details.php";  
   include($pages); 
  break; 

  case "location":
    $pages .="/farms-location.php";  
    include($pages); 
   break;

  default:   
   $pages .="/"."farms-list.php";  
  include($pages); 

 }

 
?>