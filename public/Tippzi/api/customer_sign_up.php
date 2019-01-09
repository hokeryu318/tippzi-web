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
//

if (isset($json_data)) {
    $user_name = conv_str($json_data['user_name']);
    $email = $json_data['email'];
    $gender = $json_data['gender'];
    $birthday = $json_data['birthday'];
    $username = conv_str($json_data['username']);
    $password = md5($json_data['password']);
    $lat = $json_data['lat'];
    $lon = $json_data['lon'];
    $social_account = $json_data['social_account'];
} elseif (isset($_REQUEST['user_name'])) {
    $user_name = conv_str($_REQUEST['user_name']);
    $email = $_REQUEST['email'];
    $gender = $_REQUEST['gender'];
    $birthday = $_REQUEST['birthday'];
    $username = conv_str($_REQUEST['username']);
    $password = md5($_REQUEST['password']);
    $lat = $_REQUEST['lat'];
    $lon = $_REQUEST['lon'];
    $social_account = $_REQUEST['social_account'];
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
//Check for existing user
if ($email != '') {
    $result_check_existing_user = mysqli_query($conn, "SELECT * FROM customer_user WHERE email='$email' and email!=''") or die(mysql_error());
    $result_check_existing_user3 = mysqli_query($conn, "SELECT * FROM business_user WHERE email='$email' and email!=''") or die(mysql_error());
    if (mysqli_num_rows($result_check_existing_user) == 0) {
        $result_check_existing_user2 = mysqli_query($conn, "SELECT * FROM customer_user 
													   WHERE username='$username' and username!=''") or die(mysql_error());
    }
    $result_check_existing_user4 = mysqli_query($conn, "SELECT * FROM business_user WHERE username='$username' and username!=''") or die(mysql_error());
} else {
    $result_check_existing_user = mysqli_query($conn, "SELECT * FROM customer_user 
												   WHERE user_name='$user_name' and user_name!=''") or die(mysql_error());
}

// check for empty result
$response['success'] = 'false';
$response['user_id'] = 0;
$response['social_account'] = '';

if (mysqli_num_rows($result_check_existing_user) > 0) {
    $response['message'] = 'The email is registered already.';
} elseif (mysqli_num_rows($result_check_existing_user2) > 0) {
    $response['message'] = 'The name is registered already.';
} elseif (mysqli_num_rows($result_check_existing_user3) > 0) {
    $response['message'] = 'The email is registered already.';
} elseif (mysqli_num_rows($result_check_existing_user4) > 0) {
    $response['message'] = 'The name is registered already.';
} else {
    $result = mysqli_query($conn, "INSERT INTO customer_user(user_name, 
                                                    email,
                                                    gender,
                                                    birthday,
                                                    username,
                                                    password,
                                                    social_account) 
                                            VALUES ('$user_name', 
                                                    '$email',
                                                    '$gender',
                                                    '$birthday',
                                                    '$username', 
                                                    '$password',
                                                '$social_account')");
    // check if row inserted or not
    if ($result) {
        $return_userid = $conn->insert_id;

        $response['success'] = 'true';
        $response['message'] = 'Your account is successfully created in here.';
        $response['user_id'] = $return_userid;
        $response['social_account'] = $social_account;
    } else {
        // failed to insert row
        $response['success'] = 'false';
        $response['message'] = 'Oops! An error occurred. Error description: '.mysqli_error($conn);
    }
}

echo json_encode($response);
mysqli_close($conn);
