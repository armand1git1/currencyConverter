<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
include_once '../class/currencyconverterapi.php';
include_once '../init.php';
class currencylists
{
    private $logger; // logger that will be passed to object class, making it easy to test
    // Constructor to initialize the logger

    public function __construct(Logger $logger)
    {

        $this->logger = $logger;
    }
    public function saveCurrencies($currenciesArray, $filePath)
    {

        // Ensure the directory exists
        $directory = dirname($filePath);
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0777, true) && !is_dir($directory)) {
                $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::ERROR));
                $this->logger->ERROR('Failed to create directory:' . $directory);
                return [
                    'status' => 500,
                    'error' => 'Failed to create directory: ' . $directory
                ];
            }
        }

        // Convert the array to JSON
        $json = json_encode($currenciesArray, JSON_PRETTY_PRINT);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::WARNING));
            $this->logger->ERROR('Failed to encode currencies to JSON: ' . json_last_error_msg());
            return [
                'status' => 500,
                'error' => 'Failed to encode currencies to JSON: ' . json_last_error_msg()
            ];
        }
        try {
            // Convert the array of objects to a JSON string
            $json = json_encode($currenciesArray, JSON_PRETTY_PRINT);
            // Check if JSON encoding was successful
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::WARNING));
                $this->logger->WARNING('Failed to encode currencies to JSON: ' . json_last_error_msg());
                return [
                    'status' => 500,
                    'error' => 'Failed to encode currencies to JSON: ' . json_last_error_msg()
                ];
            }

            // Save the JSON string to a file
            if (file_put_contents($filePath, $json) === false) {
                $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::ERROR));
                $this->logger->ERROR('Failed to encode currencies to JSON: ' . json_last_error_msg());
                return [
                    'status' => 500,
                    'error' => 'Failed to save currencies to file: ' . $filePath
                ];
            }

            $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::INFO));
            $this->logger->info('Currencies saved successfully to' . $filePath . '\n');
            $response = file_get_contents($filePath);
            $data =''; 
            if (isset($response)) {               
                $data = json_decode($response, true);
            }
            return [
                'status' => 200,
                'success' => 'Currencies saved successfully to' . $filePath . '\n',
                'data' => $data
            ];
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::ERROR));
            $this->logger->ERROR($e->getMessage());
            // throw new Exception("Error: " . $e->getMessage());
            return [
                'status' => 400,
                'error' => $e->getMessage()
            ];
        }
    }




    // Load supported currencies from the JSON file
    function getSupportedcurrencies($filePath)
    {
        try {
            if (!file_exists($filePath)) {
                $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::ERROR));
                $this->logger->ERROR('Currencies file not found.', ['file' => $filePath]);
                return [
                    'status' => 404,
                    'error' => 'Currencies file not found.'
                ];
            }

            $response = file_get_contents($filePath);
            if (is_object($response)) {
                $response = json_encode($response);
            }
            // Decode the JSON response into an associative array if it is a string
            if (is_string($response)) {
                $decodedResponse = json_decode($response, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::ERROR));
                    $this->logger->ERROR('Invalid JSON response', ['file' => $filePath, 'response' => $response]);
                    return [
                        'status' => 400,
                        'error' => 'Invalid JSON response: ' . json_last_error_msg()
                    ];
                }
            } else {
                // If the response is already an array, use it directly
                $decodedResponse = $response;
            }

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::ERROR));
                $this->logger->Error('Failed to parse currencies JSON file', ['file' => $filePath, 'response' => ""]);
                return [
                    'status' => 500,
                    'error' => 'Failed to parse currencies JSON file '
                ];
            }

            return [
                'status' => 200,
                'data' => $decodedResponse ?? []
            ];
        } catch (Exception $e) {
            $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::ERROR));
            $this->logger->Error('Error occurred while loading currencies', ['error' => $e->getMessage()]);
            return [
                'status' => 500,
                'error' => 'Error occurred while loading currencies: ' . $e->getMessage()
            ];
        }
    }

    // Get currency name by code
    function getCurrencyNameByCode($data, $code)
    {
        foreach ($data as $currency) {
            if ($currency['code'] === $code) {
                return $currency['name'];
            }
        }
        return "Currency code not found.";
    }
    // Validate currencies
    function validateCurrencies($cur1, $cur2, $amount, $decimal)
    {
        try {
            $supportedCurrencies = $this->getSupportedcurrencies(__DIR__ . '/../data/currencies.json');
            // Extract the 'code' values from the array of currencies
            $supportedCurrencycodes = array();
            if (isset($supportedCurrencies['data']) && is_array($supportedCurrencies['data'])) {
                $supportedCurrencycodes = array_column($supportedCurrencies['data'], 'code');
            }

            if (!in_array($cur1, $supportedCurrencycodes) || !in_array($cur2, $supportedCurrencycodes)) {
                $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::ERROR));
                $this->logger->Error('Error:' . 'Unsupported currency: ' . $cur1 . ' , ' . $cur2 . ' , amount  required.');
                return [
                    'status' => 400,
                    'error' => 'Unsupported currency: ' . $cur1 . ' , ' . $cur2 . ' , amount  required.'
                ];

            }


            //echo $amount;
            $amount = str_replace(',', '.', $amount);
            if (filter_var($amount, FILTER_VALIDATE_INT) === false || $amount < 0) {
                $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::WARNING));
                $this->logger->warning('Warning:' . 'Invalid amount. It must be an integer greater or equals to 0.');
                return [
                    'status' => 400,
                    'error' => 'Invalid amount. It must be an integer greater or equals to 0.'
                ];
            }


            $decimal = str_replace(',', '.', $decimal);
            $currencyName = "";

            if (isset($supportedCurrencies['data']) && is_array($supportedCurrencies['data'])) {

                $currencyName = $this->getCurrencyNameByCode($supportedCurrencies['data'], $cur2);
            }



            if (filter_var($decimal, FILTER_VALIDATE_INT) === false || $decimal < 0) {
                $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::WARNING));
                $this->logger->warning('Warning:' . 'Invalid decimal value. It must be an integer greater or equals to 0.');
                return [
                    'status' => 400,
                    'error' => 'Invalid decimal value. It must be an integer greater or equals to 0.'
                ];
            }

            return [
                'status' => 200,
                'message' => 'Currencies and amount are valid.',
                'currency_name' => $currencyName,
            ];
        } catch (Exception $e) {
            $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/api.log', Logger::ERROR));
            $this->logger->Error(json_encode(['error' => $e->getMessage()]));
            return [
                'status' => 500,
                'error' => json_encode(['error' => $e->getMessage()])
            ];

        }
    }

}

?>