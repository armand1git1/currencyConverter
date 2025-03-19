<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../config/Database.php';
include_once '../class/Items.php';
 
$database = new Database();
$db = $database->getConnection();
 
$items = new Items($db);
 
$data = json_decode(file_get_contents("php://input"));



if(!empty($data->name) && !empty($data->location) &&
!empty($data->established)){    

    $items->name = $data->name;
    $items->location = $data->location;
    $items->established = $data->established;
      
    if($items->create()){         
        http_response_code(201);         
        echo json_encode(array("message" => "Farm was created."));
    } else{         
        http_response_code(503);        
        echo json_encode(array("message" => "Unable to create Farm."));
    }
}else{    
    http_response_code(400);    
    echo json_encode(array("message" => "Unable to create Farm. Data is incomplete."));
}
?>