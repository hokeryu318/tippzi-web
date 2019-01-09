function requestUrl()
{
	var api_path = "http://www.compartamospyxis.com/pomodoro/Tippzi/api/";
	var request_url = $('#request_url').val();
	var json_params = $('#json_data').val(); 

	request_url = request_url.toString().trim();
	if (request_url == '')
	{
		alert ('Input a correct url.');
		return false;
	}

	var module = json_params.substring(0, json_params.indexOf("{")).trim();
	if (module == '')
	{
		alert ('Correct Parameters: module.php{JSON data}');
		$('#response_data').val(''); 
		return false;
	}
	request_url = api_path + module;
	$('#request_url').val(request_url);
	
	json_params = json_params.substring(json_params.indexOf("{"));
	$('#json_data').val(json_params); 
	
	// send JSON data to server via POST
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", request_url, false);
	xmlhttp.setRequestHeader("Content-type", "application/json");
	xmlhttp.send(json_params);

	var response = xmlhttp.responseText;
	if (verifyJson(response))
	{
		var json_pretty = JSON.stringify(JSON.parse(response), null, 2);
		$('#response_data').val(json_pretty);
	}
	else
	{
		$('#response_data').val("ERROR:\n\n" + response);
	}
}

function verifyJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function deleteResponse()
{
	$('#response_data').val('');
}

function deleteParameters()
{
	$('#json_data').val('');
	$('#response_data').val('');
}










