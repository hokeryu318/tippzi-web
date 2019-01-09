<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/Tippzi/db_config.php';
require_once __DIR__.'/function.php';

// receive JSON data sent from client via POST
$json_data = json_decode(file_get_contents('php://input'), true);
//print_r ($request_data); exit();

$response = array();
if (isset($json_data)) {
    $address = $json_data['address'];
} elseif (isset($_REQUEST['address'])) {
    $address = $_REQUEST['address'];
} else {
    // required field is missing
    $response['success'] = 'false';
    $response['message'] = 'Required field(s) is(are) missing.';
         echo json_encode($response);
    exit();
}

$response['success'] = 'true';
$response['message'] = 'Success to get location.';
$response['data'] = getCoordinates($address);

// echoing JSON response
echo json_encode($response);
