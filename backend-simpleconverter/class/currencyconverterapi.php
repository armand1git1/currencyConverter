<?php
use Curl\Curl;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
include_once '../init.php';

class CurrencyConverterApi
{
	private $curl; // curl that will be passed to object class, making it easy to test
	private $logger; // logger that will be passed to object class, making it easy to test
	public function __construct(Curl $curl, Logger $logger)
	{
		$this->curl = $curl;
		//$logger = new Logger('api');
		$this->logger = $logger;
	}
	
	private function getMyheader($url ="")
	{
		// AUthentification to the API 
		$apiKey = null;
		if ($_ENV['ApiKey'] != null) {
			$apiKey = $_ENV['ApiKey'];
		}else{
			// Access the environment variable
		  if ($_ENV['ApiKey'] != null) {
			$this->logger->pushHandler(new StreamHandler(__DIR__ . '/logs/api.log', Logger::WARNING));
			$this->logger->warning('API request failed', ['url' => $url, 'error' => 'No Api key.']);
   			throw new Exception('No Api key.');
		  }		
		}
		return [
			"Authorization: ApiKey $apiKey",
			'Content-Type: application/json',
			'Cache-Control: no-cache'
		];
	}

	private function intitializedApi()
	{
		// settings the time out to 30 second 
		$this->curl->setOpt(CURLOPT_TIMEOUT, getenv('CURL_TIMEOUT') ?: 30);
		$this->curl->setOpt(CURLOPT_HTTPHEADER, $this->getMyheader("https://swop.cx/rest/rates"));

	}
	public function CallAPI($method, $url, $data = "")
	{
		
		$this->intitializedApi();

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
				$this->logger->pushHandler(new StreamHandler(__DIR__ . '/logs/api.log', Logger::ERROR));
				$this->logger->warning('API request failed', ['url' => $url, 'error' => $this->curl->errorMessage]);
				throw new Exception('Error: ' . $this->curl->errorCode . ': ' . $this->curl->errorMessage);
			}
             
			$response = $this->curl->response;
            if (!is_array($response)) {
				$this->logger->pushHandler(new StreamHandler(__DIR__ . '/logs/api.log', Logger::ERROR));
				$this->logger->warning('Data format is wrong ', ['url' => $url, 'error' => 'Invalid API response format']);
				throw new Exception('Invalid API response format');
			}
			return $response;

		} catch (Exception $e) {
								 
			// use logging library like Monolog for structured logging 
			
			$this->logger->pushHandler(new StreamHandler(__DIR__ . '/logs/api.log', Logger::WARNING));
			$this->logger->warning('API request failed', ['url' => $url, 'error' => $e->getMessage()]);
			throw new Exception('error: ' .  $e->getMessage());

		}
	}




}
?>