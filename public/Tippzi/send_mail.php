<?php
	$to      = "ocslyan@gmail";
	$subject = "test";
	$message = "mail test";
	$headers = 'From: alexlee@gmail.com' . "\r\n" .
	'Reply-To: webmaster@example.com' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	mail($to, $subject, $message, $headers);
//    mysqli_close($con);
//    exit();
    
?>