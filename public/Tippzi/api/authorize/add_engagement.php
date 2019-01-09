<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/Tippzi/db_config.php';
require_once __DIR__.'/function.php';

// array for JSON response
$response = array();

// connect to database
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
if ($conn->connect_error) {
    die('Connection failed: '.$conn->connect_error);
}

// receive JSON data sent from client via POST
$json_data = json_decode(file_get_contents('php://input'), true);
//print_r ($request_data); exit();

if (isset($json_data)) {
    $user_id = $json_data['user_id'];
    $bar_id = $json_data['bar_id'];
    $qty = $json_data['qty'];
} elseif (isset($_REQUEST['user_id'])) {
    //	$user_id = $_REQUEST['user_id'];
    //	$deal_id = $_REQUEST['bar_id_list'];
    //	$engagement = $_REQUEST['engagement_list'];
    $user_id = $_REQUEST['user_id'];
    $bar_id = $_REQUEST['bar_id'];
    $qty = $_REQUEST['qty'];
} else {
    // required field is missing
    $response['success'] = 'false';
    $response['message'] = 'Required field(s) is(are) missing.';

    // echoing JSON response
    echo json_encode($response);

    //Close connection
    mysqli_close($conn);

    exit();
}
for ($i = 0; $i < $qty; ++$i) {
    $wallert_insert = mysqli_query($conn, "insert into clicks(`bar_id`,`user_id`) value ('$bar_id','$user_id')");
}

$response['success'] = 'true';
$response['message'] = 'Successfully Insert.';

// echoing JSON response
echo json_encode($response);
//Close connection
mysqli_close($conn);
