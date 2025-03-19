<?php
if (!defined('MAINFILE_INCLUDED')) 
{
   die('You cannot access this file directly !');exit;
}   


// Return number of month between two dates. 
function nbr_month_2dates($date1, $date2) {
  $date2_year="";
  $date2_month="";
  
  $date1_year ="";
  $date1_month="";
  $year_diff  ="";
  $month_diff ="";
  

  if (isset($date2)) {
    $arr_explode_date =explode("-",$date2);
    $date2_year       =$arr_explode_date[0]; 
    $date2_month      =$arr_explode_date[1];
  }

  if (isset($date1)) {
    $arr_explode_date = explode("-",$date1);
    $date1_year       = $arr_explode_date[0]; 
    $date1_month      = $arr_explode_date[1];
  }

  
  $year_diff         = ($date2_year -  $date1_year)*12;
  $month_diff        = ($date2_month + $year_diff) - $date1_month;      

 
  return $month_diff;
}







function return_current_url($lang,$actual_link="",$module_new="")
{ 
 $word_parts   = "";  

 // step 1 : Treat from the language 
 if (isset($lang))
 {

   $index_lang1     =strpos( $actual_link,"?");        // checking if the current link contains "?" 
   if ($index_lang1===FALSE)    $actual_link =$actual_link."?";  
  
   if($lang=="fr")
   { 
    $index_lang21    =strpos( $actual_link,"lang=fr");        // validate the operations 
    if ($index_lang21===FALSE) 
    {
      $index_lang2     =strpos( $actual_link,"lang");        // checking if the current link contains "?" 
      if ($index_lang2!==FALSE)  
      {
       $word_parts  = explode("?lang=en", $actual_link);        
      } 
      else{ 
       $word_parts  = explode("?", $actual_link);       
      }     
     $actual_link     = $word_parts[0]."?"."lang=fr";  
    }         
   }  
  
   if($lang=="en")
   {
    $index_lang21    =strpos( $actual_link,"lang=en");        // validate the operations 
    if ($index_lang21===FALSE) 
    {

      $index_lang2     =strpos( $actual_link,"lang");        // checking if the current link contains "?" 
      if ($index_lang2!==FALSE)  
      {
       $word_parts  = explode("?lang=fr", $actual_link);             
      } 
      else{ 
       $word_parts  = explode("?", $actual_link);       
      }        
      $actual_link = $word_parts[0]."?"."lang=en";     
    }
   } 

  if (isset($word_parts[1])) $actual_link=$actual_link.$word_parts[1];  
 }
 // step 2 : Treat from the module   
 if((isset($module_new)) && $module_new!="")
 { 

   $index_moudule1    =strpos( $actual_link,"module=");        // validate the operations 
   if ($index_moudule1!==FALSE)  
   {
     $word_parts  = explode("&module", $actual_link); 
     $actual_link = $word_parts[0];          
  } 

    
     //$word_parts  = explode("module", $actual_link);
     $actual_link = $actual_link."&module=".$module_new;
 } 
 return $actual_link; 
}



 
 function ip2long1($ip, $getVersion = TRUE)
 {
   $version = valVersion($ip);
   if($getVersion === FALSE && $version === FALSE)return FALSE;
   if($getVersion === FALSE && $version === 'ipv4')return ip2long_v4($ip);
   if($getVersion === FALSE && $version === 'ipv6')return ip2long_v6($ip);

   if($getVersion === TRUE && $version === FALSE)return array('version' => FALSE, 'int' => FALSE);
   if($getVersion === TRUE && $version === 'ipv4')return array('version' => $version, 'int' => ip2long_v4($ip));
   if($getVersion === TRUE && $version === 'ipv6')return array('version' => $version, 'int' => ip2long_v6($ip));

    return trigger_error('inalid argument getVersion in ipFormat::ip2long()!', E_USER_ERROR);
  }
  //eof ip2long

 function valVersion($ip)
 {
     if(filter_var($ip, FILTER_FLAG_IPV4))return 'ipv4';
     if(filter_var($ip, FILTER_FLAG_IPV6))return 'ipv6';
     return FALSE;
  }
  //eof valVersion

 function ip2long_v4($ip)
 {
    list(, $result) = unpack('l',pack('l',ip2long($ip) )  );
    return $result;
 }

 function ip2long_v6($ip) {
    $ip_n = inet_pton($ip);
    $bin = '';
    for ($bit = strlen($ip_n) - 1; $bit >= 0; $bit--) {
        $bin = sprintf('%08b', ord($ip_n[$bit])) . $bin;
    }

    if (function_exists('gmp_init')) {
        return gmp_strval(gmp_init($bin, 2), 10);
    } elseif (function_exists('bcadd')) {
        $dec = '0';
        for ($i = 0; $i < strlen($bin); $i++) {
            $dec = bcmul($dec, '2', 0);
            $dec = bcadd($dec, $bin[$i], 0);
        }
        return $dec;
    } else {
        trigger_error('GMP or BCMATH extension not installed!', E_USER_ERROR);
    }
 }

 function long2ip_v6($dec) {
    if (function_exists('gmp_init')) {
        $bin = gmp_strval(gmp_init($dec, 10), 2);
    } elseif (function_exists('bcadd')) {
        $bin = '';
        do {
            $bin = bcmod($dec, '2') . $bin;
            $dec = bcdiv($dec, '2', 0);
        } while (bccomp($dec, '0'));
    } else {
        trigger_error('GMP or BCMATH extension not installed!', E_USER_ERROR);
    }

    $bin = str_pad($bin, 128, '0', STR_PAD_LEFT);
    $ip = array();
    for ($bit = 0; $bit <= 7; $bit++) {
        $bin_part = substr($bin, $bit * 16, 16);
        $ip[] = dechex(bindec($bin_part));
    }
    $ip = implode(':', $ip);
    return inet_ntop(inet_pton($ip));
 }      

// Getting Ip address
function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}  
 


function format_calendar_date($date_posted) {
 if (isset($date_posted) && $date_posted!=null) {
  if(strpos($date_posted, "/") !== false){
    $arr_date = explode("/", $date_posted); 
    if (is_array($arr_date)) {
      $date_posted =$arr_date[2]."-".$arr_date[1]."-".$arr_date[0];  
    }    
  }
 }
 return $date_posted;      
}
?>