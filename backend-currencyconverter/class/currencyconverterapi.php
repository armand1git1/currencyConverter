<?php
use Curl\Curl;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;
include_once '../init.php';

class CurrencyConverterApi
{
	private $curl; // curl that will be passed to object class, making it easy to test
	private $logger; // logger that will be passed to object class, making it easy to test
	private $varCahe;
	public function __construct(Curl $curl, Logger $logger, Psr16Cache $varCahe)
	{
		$this->curl = $curl;
		$this->logger = $logger;
		$this->varCahe = $varCahe;
	}

	private function getMyheader($url = "")
	{
		// AUthentification to the API 
		$apiKey = null;
		if ($_ENV['ApiKey'] != null) {
			$apiKey = $_ENV['ApiKey'];
		} else {
			// Access the environment variable
			if ($_ENV['ApiKey'] != null) {
				$this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::WARNING));
				$this->logger->warning('API request failed', ['url' => $url, 'error' => 'No Api key.']);
				return [
					'status' => 500,
					'error' => 'No Api key.'
				];
				// throw new Exception('No Api key.');
			}
		}
		return [
			"Authorization: ApiKey $apiKey",
			'Content-Type: application/json',
			'Cache-Control: no-cache'
		];
	}

	private function intitializedApi($url)
	{
		// settings the time out to 30 second 
		$this->curl->setOpt(CURLOPT_TIMEOUT, getenv('CURL_TIMEOUT') ?: 30);
		$this->curl->setOpt(CURLOPT_HTTPHEADER, $this->getMyheader($url));
	}
	public function CallAPI($method, $url, $data = "",$action="")
	{

		$this->intitializedApi($url);
		$arrayConversion = array();
		// Check if the response is cached
		$cacheKey = md5($method . $url . json_encode($data));
		if ($this->varCahe->has($cacheKey)) {
			$this->logger->info('Cache hit', ['url' => $url]);
			return $this->varCahe->get($cacheKey);
		}

		try {
			switch ($method) {
				case "POST":
					$this->curl->post($url, $data);
					break;
				case "PUT":
					$this->curl->put($url, $data);
					break;
				default:
					$this->curl->get($url);
			}

			if ($this->curl->error) {
				$this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::ERROR));
				$this->logger->warning('API request failed', ['url' => $url, 'error' => $this->curl->errorMessage]);
				// throw new Exception('Error: ' . $this->curl->errorCode . ': ' . $this->curl->errorMessage);
				return [
					'status' => 500,
					'error' => 'Error: ' . $this->curl->errorCode . ': ' . $this->curl->errorMessage
				];
			}

			$response = $this->curl->response;
			// print_r($response);
			//exit();

			// Convert the response to a string if it is an object
			if (is_object($response)) {
				$response = json_encode($response); // Convert the object to a JSON string
			}
			// Decode the JSON response into an associative array if it is a string
			if (is_string($response)) {
				$decodedResponse = json_decode($response, true);
				if (json_last_error() !== JSON_ERROR_NONE) {
					$this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::ERROR));
					$this->logger->warning('Invalid JSON response', ['url' => $url, 'response' => $response]);
					return [
						'status' => 500,
						'error' => 'Invalid JSON response: ' . json_last_error_msg() 
					];
				}
			} else {
				// If the response is already an array, use it directly
				$decodedResponse = $response;
			}

			
			// check if  the response is an array 
			if (!is_array($decodedResponse)) {
				$this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::ERROR));
				$this->logger->warning('Unexpected response format', ['url' => $url, 'response' => $decodedResponse]);
				return [
					'status' => 500,
					'error' => 'Error: Unexpected response format: ' 
				];
			}
            
			if ($action === "convertCurrency") {
			// Validate the response structure
			if (!isset($decodedResponse['base_currency'], $decodedResponse['quote_currency'], $decodedResponse['quote'], $decodedResponse['date'])) {
				$this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::ERROR));
				$this->logger->error('Unexpected response format', ['url' => $url, 'response' => $decodedResponse]);
				return [
					'status' => 500,
					'error' => 'Error: Unexpected response format: ' 
				];
			}

			// Cache the response
			$this->varCahe->set($cacheKey, $decodedResponse, 3600); // Cache for 1 hour

			// var_dump($response);
			$arrayConversion['base_currency'] = $decodedResponse['base_currency'];
			$arrayConversion['quote_currency'] = $decodedResponse['quote_currency'];
			$arrayConversion['quote'] = $decodedResponse['quote'];
			$arrayConversion['date'] = $decodedResponse['date'];

			// Calculate the converted amount if 'quote' and 'data' are available
			if (isset($decodedResponse['quote']) && $data) {
				$arrayConversion['convertedAmount'] = $decodedResponse['quote'] * $data;
			} else {
				$arrayConversion['convertedAmount'] = null;
			}

			return $arrayConversion;
		}else{
			return $decodedResponse;
		}

		} catch (Exception $e) {
			$this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::WARNING));
			$this->logger->warning('API request failed', ['url' => $url, 'error' => $e->getMessage()]);
			return [
				'status' => 500,
				'error' => 'Error occurred while loading currencies: ' . $e->getMessage()
			];		

		}
	}




}
?>