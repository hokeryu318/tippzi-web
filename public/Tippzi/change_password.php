<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Tippzi/db_config.php';
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
}

if(isset($_REQUEST["token"])){
	$token = $_REQUEST["token"];
} else {
	$token = "" ;
}

$currentdate = date('Y-m-d') ;
$sql = "select * from password_reset where token = '$token'" ;
$res = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($res);
if($row["created_at"] < $currentdate) {
	mysqli_query($conn,"delete from password_reset where token='$token'");
}

$sql = "select * from password_reset where token = '$token'" ;
$res = mysqli_query($conn, $sql);
$num = mysqli_num_rows($res);
if($num > 0) {
	if(isset($_POST["newPassword"])) 
	{
		if(isset($_POST["confirmPassword"])){
			if($_POST["newPassword"] || $_POST["confirmPassword"]){
				if($_POST['newPassword'] == $_POST["confirmPassword"]) {
					$result = mysqli_query($conn,"SELECT * from password_reset WHERE token='" . $token . "'");
					$row=mysqli_fetch_array($result);

					if ($row) {
							$password = md5($_POST["newPassword"]);
							$user_id = $row["uid"] ;
							$check_customeruser = mysqli_query($conn, "select * from customer_user where Id='$user_id'");
							$check_businessuser = mysqli_query($conn, "select * from business_user where Id='$user_id'");
							if( mysqli_num_rows($check_customeruser) > 0 ) {
								if(!mysqli_query($conn,"UPDATE customer_user set password='" . $password . "' WHERE Id='" . $row["uid"] . "'")){
									$message = "Your passowrd is not updated." ;			
								} else {
									$message = "Your password has been successfully updated." ;
									mysqli_query($conn,"delete from password_reset where token='".$token."'");
								}								
							} else if (mysqli_num_rows($check_businessuser) > 0 ) {
								if(!mysqli_query($conn,"UPDATE business_user set password='" . $password . "' WHERE Id='" . $row["uid"] . "'")){
									$message = "Your passowrd is not updated." ;			
								} else {
									$message = "Your password has been successfully updated." ;
									mysqli_query($conn,"delete from password_reset where token='".$token."'");
								}								
							}
					} else {}				
				} else {}
			}
		}
	}
	else{
		$message = "" ;
	}	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Eventually by HTML5 UP</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<script>
		function validatePassword() {
		var newPassword,confirmPassword,output = true;

		newPassword = document.frmChange.newPassword;
		confirmPassword = document.frmChange.confirmPassword;
		var length = document.frmChange.newPassword.value.length
		if(!newPassword.value) {
			newPassword.focus();
			newPassword.style.borderColor = null;
			confirmPassword.style.borderColor = null;
			document.getElementById("newPassword").innerHTML = "Please fill out field.";
			document.getElementById("confirmPassword").innerHTML = "" ;
			output = false;
		}
		else if(!confirmPassword.value) {
			confirmPassword.focus();
			document.getElementById("newPassword").innerHTML = "";
			document.getElementById("confirmPassword").innerHTML = "Please fill out field.";
			output = false;
		} else if(length < 8 ){
			newPassword.style.borderColor = "red";
			confirmPassword.style.borderColor = null;
			document.getElementById("confirmPassword").innerHTML = "" ;
			document.getElementById("newPassword").innerHTML = "Should be more than 8 characters.";
			output = false;
		}else if(newPassword.value != confirmPassword.value) {
			newPassword.style.borderColor = null;
			confirmPassword.style.borderColor = "red";
			document.getElementById("newPassword").innerHTML = "";
			document.getElementById("confirmPassword").innerHTML = "Confirmation password must your entered password.";
			output = false;
		} 	
		return output;
		}
		</script>
	</head>
<body>
	<header id="header">
		<h1><center>Reset Password</center></h1>
		<center><p>You can reset your current password by entering a new one in the boxes below</p></center>
		<center><p class="description">Please enter matching passwords. Passwords should be more than 8 characters.</p></center>
	</header>
	<!-- Signup Form -->
	<form name="frmChange" method="post" action="" onSubmit="return validatePassword()">
		<p><span><center><?php if(isset($message)) { echo $message; } ?></center></span></p>
		<input type="password" name="newPassword" placeholder="New Password" />
		<p><span id="newPassword" class="required"></span></p>
		<input type="password" name="confirmPassword" placeholder="Confirm Password" />
		<p><span id="confirmPassword" class="required"></p>
		<center><input type="submit" name="submit" value="Submit" class="btnSubmit"></center>
		<br/>
	</form>
</body>
</html>
<?php
} else
{
	?>
<!DOCTYPE html>
<html>
	<head>
		<title>Eventually by HTML5 UP</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
<body>
	<header id="header">
		<h1><center>Reset Password</center></h1>
		<center><p>Error, this link has expired or the token doesn't exist</p></center>
	</header>
</body>
</html>	
	<?php
}
?>