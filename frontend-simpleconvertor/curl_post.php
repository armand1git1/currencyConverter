<?php
$data = array(
    'username' => 'abcdss',
    'password' => 'barbarbar',
    //'email' => 'bazbazbaz@ahoo.fr'
    'email' => 'totta@ahoo.fr'
); // data u want to post                                                                   
$data_string = json_encode($data);                                                                                   
$user = "willy";   
$password = "willy";                                                                                                                 
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, "https://api-wtm.konguem.eu/accounts/register");    
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
curl_setopt($ch, CURLOPT_POST, true);                                                                   
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
curl_setopt($ch, CURLOPT_USERPWD, $user.':'.$password);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
    'Accept: application/json',
    'Content-Type: application/json')                                                           
);             

if(curl_exec($ch) === false)
{
    echo 'Curl error: ' . curl_error($ch);
}                                                                                                      
$errors = curl_error($ch);                                                                                                            
$result = curl_exec($ch);
$returnCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);  
echo $returnCode;
var_dump($errors);
print_r(json_decode($result, true));
?>