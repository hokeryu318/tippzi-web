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
    $username = $json_data['username'];
} elseif (isset($_REQUEST['username'])) {
    $username = $_REQUEST['username'];
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
$check_customeruser = mysqli_query($conn, "SELECT * FROM customer_user WHERE email='$username' or username='$username'") or die(mysql_error());
$check_businessuser = mysqli_query($conn, "SELECT * FROM business_user WHERE email='$username'") or die(mysql_error());

if (mysqli_num_rows($check_customeruser) > 0) {
    $response['success'] = 'true';
    $response['message'] = 'Welcome';
    $response['name'] = $username;

    echo json_encode($response);

    $row = mysqli_fetch_array($check_customeruser);

    $current_time = date('Y-m-d H:i:s');
    $token = bin2hex(openssl_random_pseudo_bytes(16));
    $hashedToken = md5((string) $current_time);
    $createdby = date('Y-m-d');

    $uid = $row['Id'];
    $email = $row['email'];
    $sql = "select * from password_reset where uid = '".$uid."'";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        $row1 = mysqli_fetch_array($res);

        $to = $row1['emailAddress'];

        mysqli_close($conn);
        $subject = 'Tippzi Password Reset';
        $message = 'https://tippzi.com/Tippzi/change_password.php?token='.$row1['token'];

        $url = 'https://api.sendgrid.com/';
        $user = 'RenHe';
        $pass = 'Starcandy1';

        $json_string = array(
                'to' => array($to,
            ),
            'category' => 'test_category',
        );

        $params = array(
                'api_user' => $user,
                'api_key' => $pass,
                'x-smtpapi' => json_encode($json_string),
                'to' => $to,
                'subject' => $subject,
                'html' => $message,
                'text' => $message,
                'from' => 'www.tippzi.com',
    );

        $request = $url.'api/mail.send.json';

        // Generate curl request
        $session = curl_init($request);
        // Tell curl to use HTTP POST
        curl_setopt($session, CURLOPT_POST, true);
        // Tell curl that this is the body of the POST
        curl_setopt($session, CURLOPT_POSTFIELDS, $params);
        // Tell curl not to return headers, but do return the response
        curl_setopt($session, CURLOPT_HEADER, false);
        // Tell PHP not to use SSLv3 (instead opting for TLS)
        curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        // obtain response
        $response = curl_exec($session);
        curl_close($session);
    } else {
        if (mysqli_query($conn, "insert into password_reset (`uid`,
															`emailAddress`,
															`token`,
															`created_at`) 
												    values ('$uid',
													        '$email',
															'$hashedToken',
															'$createdby')")) {
            $sql1 = "select * from password_reset where emailAddress = '$email'";
            $res1 = mysqli_query($conn, $sql1);
            $row2 = mysqli_fetch_array($res1);
            $to = $row2['emailAddress'];
            mysqli_close($conn);

            $subject = 'Tippzi Password Reset';
            $message = 'http://162.13.192.72/Tippzi/change_password.php?token='.$row2['token'];
            $url = 'https://api.sendgrid.com/';
            $user = 'RenHe';
            $pass = 'Starcandy1';

            $json_string = array(
                'to' => array($to,
            ),
            'category' => 'test_category',
        );

            $params = array(
                'api_user' => $user,
                'api_key' => $pass,
                'x-smtpapi' => json_encode($json_string),
                'to' => $to,
                'subject' => $subject,
                'html' => $message,
                'text' => $message,
                'from' => 'www.tippzi.com',
    );

            $request = $url.'api/mail.send.json';

            // Generate curl request
            $session = curl_init($request);
            // Tell curl to use HTTP POST
            curl_setopt($session, CURLOPT_POST, true);
            // Tell curl that this is the body of the POST
            curl_setopt($session, CURLOPT_POSTFIELDS, $params);
            // Tell curl not to return headers, but do return the response
            curl_setopt($session, CURLOPT_HEADER, false);
            // Tell PHP not to use SSLv3 (instead opting for TLS)
            curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

            // obtain response
            $response = curl_exec($session);
            curl_close($session);
        }
    }
} elseif (mysqli_num_rows($check_businessuser) > 0) {
    $response['success'] = 'true';
    $response['message'] = 'Welcome';
    echo json_encode($response);

    $row = mysqli_fetch_array($check_businessuser);

    $current_time = date('Y-m-d H:i:s');
    $token = bin2hex(openssl_random_pseudo_bytes(16));
    $hashedToken = md5((string) $current_time);
    $createdby = date('Y-m-d');

    $uid = $row['Id'];
    $email = $row['email'];
    $sql = "select * from password_reset where uid = '".$uid."'";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) > 0) {
        $row1 = mysqli_fetch_array($res);

        $to = $row1['emailAddress'];
        mysqli_close($conn);
        $subject = 'Tippzi Password Reset';
        $message = 'http://162.13.192.72/Tippzi/change_password.php?token='.$row2['token'];
        $url = 'https://api.sendgrid.com/';
        $user = 'RenHe';
        $pass = 'Starcandy1';

        $json_string = array(
                'to' => array($to,
            ),
            'category' => 'test_category',
        );

        $params = array(
                'api_user' => $user,
                'api_key' => $pass,
                'x-smtpapi' => json_encode($json_string),
                'to' => $to,
                'subject' => $subject,
                'html' => $message,
                'text' => $message,
                'from' => 'www.tippzi.com',
    );

        $request = $url.'api/mail.send.json';

        // Generate curl request
        $session = curl_init($request);
        // Tell curl to use HTTP POST
        curl_setopt($session, CURLOPT_POST, true);
        // Tell curl that this is the body of the POST
        curl_setopt($session, CURLOPT_POSTFIELDS, $params);
        // Tell curl not to return headers, but do return the response
        curl_setopt($session, CURLOPT_HEADER, false);
        // Tell PHP not to use SSLv3 (instead opting for TLS)
        curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        // obtain response
        $response = curl_exec($session);
        curl_close($session);
    } else {
        if (mysqli_query($conn, "insert into password_reset (`uid`,
															`emailAddress`,
															`token`,
															`created_at`) 
												    values ('$uid',
													        '$email',
															'$hashedToken',
															'$createdby')")) {
            $sql1 = "select * from password_reset where emailAddress = '$email'";
            $res1 = mysqli_query($conn, $sql1);
            $row2 = mysqli_fetch_array($res1);
            $to = $row2['emailAddress'];
            mysqli_close($conn);

            $subject = 'Tippzi Password Reset';
            $message = 'http://162.13.192.72/Tippzi/change_password.php?token='.$row2['token'];
            $url = 'https://api.sendgrid.com/';
            $user = 'RenHe';
            $pass = 'Starcandy1';

            $json_string = array(
                'to' => array($to,
            ),
            'category' => 'test_category',
        );

            $params = array(
                'api_user' => $user,
                'api_key' => $pass,
                'x-smtpapi' => json_encode($json_string),
                'to' => $to,
                'subject' => $subject,
                'html' => $message,
                'text' => $message,
                'from' => 'www.tippzi.com',
    );

            $request = $url.'api/mail.send.json';

            // Generate curl request
            $session = curl_init($request);
            // Tell curl to use HTTP POST
            curl_setopt($session, CURLOPT_POST, true);
            // Tell curl that this is the body of the POST
            curl_setopt($session, CURLOPT_POSTFIELDS, $params);
            // Tell curl not to return headers, but do return the response
            curl_setopt($session, CURLOPT_HEADER, false);
            // Tell PHP not to use SSLv3 (instead opting for TLS)
            curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

            // obtain response
            $response = curl_exec($session);
            curl_close($session);
        }
    }
}

//Close connection
mysqli_close($conn);
