# currencyConverter
simple app use to convert currency
Frontend in Vue.js : convertor apps ( Only the conversion from euro is available with my account ) : 
https://site.walkap.net/currencyConverter/frontend-currencyconvertor/

1) Basic  Scirpts + OOP (object oriented programming) 
2) The apps : the backend have been executed following instruction of the requirement. 
3) Testing and implementation 
    3.1 : A Test version of Backend available online : hosted by Armand 
    - short video demo : https://drive.google.com/file/d/1JzOxLYDkDXmi8k98OF4LZi93iD3czPqF/view?usp=drive_link
    - solution : https://site.walkap.net/currencyConverter/backend-currencyconverter/converter/read.php?cur1=eur&cur2=usd&amount=50&amount=5&decimal=90;  
    How it works : just changed the value of cur1; cur2, amount, decimal in the url to have expected result
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
	  
	  
Fontend part :  Vue.js
https://site.walkap.net/currencyConverter/frontend-currencyconvertor/
1) Install Vue CLI
*  Open a terminal (visual studio), type :  
    npm install -g @vue/cli  
* check installation : 
   vue --version
   
2) Create a New Vue Project and navigate to the project 
   vue create currency-convertor-app
* go to directory :  cd  currency-convertor-app

3) start development server : 
   npm run serve
Acceess app locally :  http://localhost:8080

4) run production : npm run build 
 
