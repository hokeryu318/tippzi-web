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

$query = mysqli_query($conn, 'SELECT service_name FROM bar GROUP BY service_name');
while ($row = mysqli_fetch_array($query)) {
    array_push($response, $row['service_name']);
}

// echoing JSON response
echo json_encode($response);
//Close connection
mysqli_close($conn);
