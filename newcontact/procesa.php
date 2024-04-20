<?php

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

require 'Service/ApiVtiger.php';

use Rest\Service\ApiVtiger;

$url = 'http://localhost/vtigercrm/webservice.php'; 
$userName = 'admin';		
$userAccessKey = 'ab1cGe52qAi9X23M';

$contact = [
    "firstname" => $_POST["name"],
    "lastname" => $_POST["lastname"],
    "phone" => $_POST["phone"],
    "email" => $_POST["email"],
    'assigned_user_id' => $userName
];

try {
    $objApiVtiger = new ApiVtiger($url, $userName, $userAccessKey);
    $objApiVtiger->createRecord($contact, 'Contacts');
    $status = 'success';
} catch (Exception $e) {
    $status = 'error';
}

$data = [
    'status' => $status
];

echo json_encode($data);