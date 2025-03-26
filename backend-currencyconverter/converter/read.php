<?php

include_once '../class/currencyconverterapi.php';
include_once '../class/currencies.php';
include_once '../init.php';
use Curl\Curl;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

$mycurl = new Curl();
$mylogger = new Logger('api');
$cacheAdapter = new FilesystemAdapter();
$mycache = new Psr16Cache($cacheAdapter);

// Getting variable
$cur1 = filter_input(INPUT_GET, 'cur1', FILTER_SANITIZE_STRING);
$cur2 = filter_input(INPUT_GET, 'cur2', FILTER_SANITIZE_STRING);

if (filter_has_var(INPUT_GET, 'amount')) {
    $amount = filter_input(INPUT_GET, 'amount', FILTER_VALIDATE_INT);    
} else {
    $amount = 0; // Default value if 'decimal' is not provided
}

if (filter_has_var(INPUT_GET, 'decimal')) {
    $decimalPart = filter_input(INPUT_GET, 'decimal', FILTER_VALIDATE_INT);
} else {
    $decimalPart = 0; // Default value if 'decimal' is not provided
}

$method = filter_input(INPUT_GET, 'method', FILTER_SANITIZE_STRING);
$mycurrentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")
    . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

  
$converterClass = new CurrencyConverterApi($mycurl, $mylogger, $mycache);
$cur1 = strtoupper($cur1);
$cur2 = strtoupper($cur2);


$currentDay = date("Y-m-d");
$url = "https://swop.cx/rest/rates/" . $cur1 . "/" . $cur2 . "?date=" . $currentDay;


$currencyClass = new currencylists($mylogger);
//$method = $_SERVER['REQUEST_METHOD']; // Get the HTTP method;

$filePath = __DIR__ . '/../data/currencies.json';

// $method = "POST";
switch ($method) {
    case "POST":

        $url = "https://swop.cx/rest/currencies";
        $converterArray = $converterClass->CallAPI("GET", "$url", 0,"");
        $SavedCurrencylist = $currencyClass->saveCurrencies($converterArray, $filePath);
        echo json_encode($SavedCurrencylist, JSON_UNESCAPED_UNICODE);
        exit();
        //print_r($SavedterArray);
        break;
    case "PUT":
        $this->curl->put($url, $data);
        break;
    default:
        $responseValidation =$currencyClass->validateCurrencies($cur1, $cur2,$amount,$decimalPart,);
        if ($responseValidation['status'] === 200) {
            $currencyName = $responseValidation['currency_name'];
            $converterArray = $converterClass->CallAPI("GET", "$url", $amount,$decimalPart, $action="convertCurrency", $currencyName);
            echo json_encode($converterArray, JSON_UNESCAPED_UNICODE);
            exit();
            // print_r($converterArray);
        }else{
            // print_r($responseValidation);
            echo json_encode($responseValidation, JSON_UNESCAPED_UNICODE);
            exit();
            
        }         
        

}



