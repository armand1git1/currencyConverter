<?php


include_once '../class/currencyconverterapi.php';
include_once '../init.php';
use Curl\Curl;
use Monolog\Logger;
$mycurl = new Curl();
$mylogger = new Logger('api');

$converterClass = new CurrencyConverterApi( $mycurl,  $mylogger);
$converterArray = $converterClass->CallAPI("GET","https://swop.cx/rest/rates");
print_r($converterArray);
die(); 

$database = new Database();
$db = $database->getConnection();
 
$items = new Farms($db);

$items->id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';

$result = $items->read();

if($result->num_rows > 0){    
    $itemRecords=array();
	while ($item = $result->fetch_assoc()) { 	
        extract($item); 
        $itemDetails=array(
            "id" => $id,
            "name" => $name,
            "location" =>  $location,
			"established" => $established		
        ); 
        $itemDetails = array_map('utf8_encode', $itemDetails);
       array_push($itemRecords, $itemDetails);
    }    
    http_response_code(200);     
    echo json_encode($itemRecords);
}else{     
    http_response_code(404);     
    echo json_encode(
        array("message" => "No item found.")
    );
} 
