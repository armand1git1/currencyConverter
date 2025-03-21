# currencyConverter
simple app use to convert currency
1) Basic  Scirpts + OOP (object oriented programming) 
2) The apps : the backend have been executed following instruction of the requirement. 
3) Testing and implementation 
    3.1 : A Test version of Backend available online : hosted by Armand 
    link : 
    3.2 : A dockerfile will be available soon for deployment
    3.3 : Install and test locally 
      * Needed  Locally, i use : php version  7.4.25 & Apache version : [httpd-2.4.35- Wind 64 -VC 15]  
      * Package installed : On the root directory of your project : backend-currencyconverter>
      Intall the following packages : 
      - Curl :  composer require php-curl-class/php-curl-class
      - Monolog for structured logging : composer require monolog/monolog      
      - vlucas/phpdotenv to load environment variable : composer require vlucas/phpdotenv
      - symfony cache for cahe management : composer require symfony/cache:^5.4 ; composer require psr/simple-cache 
      - create .env file where is stored the api key

4) Access the test case :
if you run the website locally : 
      - End point : source currency : eur; target currency: usd; monetary value : 100  
      http://localhost/currencyConverter/backend-currencyconverter/converter/read.php?cur1=eur&cur2=usd&amount=100