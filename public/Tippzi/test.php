<?php
$api_path = "http://162.13.192.72/Tippzi/api/";
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>API tester</title>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/test.js"></script>
</head>
<body>
<br />
<table cellpadding="0" cellspacing="0" width="80%" align="center" style="width: 1170px;">
<tr>
	<td>
		<div>
			<span style="color:green;">Request url:&nbsp;&nbsp;&nbsp;</span>
			<div>
				<textarea id="request_url" readonly style="width:1165px;height:30px;font-size:20px;"><?php echo $api_path;?></textarea>
			</div>
		</div>
	</td>
</tr>
<br />
<table cellpadding="0" cellspacing="0" width="80%" align="center" style="width: 1170px;">
	<tr>
		<td width="45%">
			<br />
			<span style="color:green;">Parameters:&nbsp;&nbsp;&nbsp;&nbsp;</span>
			<a href="#" onclick="deleteParameters();">
				<span style="color:blue;font-size:18px;">Delete</span>
			</a>
			<div>
				<textarea id="json_data" style="width:520px;height: 630px; font-size:20px;"></textarea>
			</div>
		</td>
		<td width="2%"></td>
		<td width="6%">
			<input type="button" class="button1" style="height:30px; background-color:green; color:white; border-style:solid; border-color:green;" name="Request" value="Request" 
				onClick="requestUrl();" />
		</td>
		<td width="2%"></td>
		<td width="45%">
			<br />
			<span style="color:green;">Response:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
			<a href="#" onclick="deleteResponse();">
				<span style="color:blue;font-size:18px;">Delete</span>
			</a>
			<div>
				<textarea id="response_data" style="width:520px;height: 630px; font-size:20px;"></textarea>
			</div>
		</td>
	<tr>
</body>
</html>