<?php


include_once '../class/currencyconverterapi.php';
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
$amount = filter_input(INPUT_GET, 'amount', FILTER_VALIDATE_FLOAT);


// Check if required parameters are provided and valid
if (!$cur1 || !$cur2 || $amount === false || $amount <= 0) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Missing or invalid parameters: cur1, cur2, and amount are required.']);
    $mylogger->pushHandler(new StreamHandler(__DIR__ . '/logs/api.log', Logger::ERROR));
	$mylogger->error('API request failed', ['url' => $url, 'error' => 'Missing or invalid parameters: cur1, cur2, and amount are required.']);
   	throw new Exception('Missing or invalid parameters: cur1, cur2, and amount are required.');
}

$converterClass = new CurrencyConverterApi($mycurl, $mylogger, $mycache);
$cur1 = strtoupper($cur1);
$cur2 = strtoupper($cur2);
$currentDay = date("Y-m-d");
$url  ="https://swop.cx/rest/rates/". $cur1 ."/". $cur2 ."?date=". $currentDay;
echo $url;

/*
$url="https://swop.cx/rest/conversions?base_currency=CHF&quote_currencies=GBP,EUR,USD&amount=125.25";

$url = "https://swop.cx/rest/conversions/EUR/USD?amount=200";
$url = "https://swop.cx/rest/rates";

$url = "https://swop.cx/rest/rates/EUR/USD?date=2025-03-20";
echo $url;
echo "<br>";
*/




$method = $_SERVER['REQUEST_METHOD']; // Get the HTTP method;
switch ($method) {
    case "POST":
        $this->curl->post($url, $data);
        break;
    case "PUT":
        $this->curl->put($url, $data);
        break;
    default:
        $converterArray = $converterClass->CallAPI("GET", "$url", $amount);
        print_r($converterArray);

}



