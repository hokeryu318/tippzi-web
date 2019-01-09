<?php

function conv_str($str)
{
    //$str = '35% when you spend over ?150 online or in our store from hijabs,abayas and book';
    //var_dump($str);
    //   return htmlspecialchars();
    //return str_replace("'", "\'",$str);
    //var_dump(htmlentities($str));
    //var_dump(htmlspecialchars($str, ENT_COMPAT ,'ISO-8859-15', true));

    //exit;
    //return html_entities($str);

    $str = get_repace_string($str);
    $result = htmlspecialchars($str, ENT_COMPAT, 'UTF-8', true);
    $result = str_replace('&amp;pound;', '&pound;', $result);

    return $result;
}

function get_repace_string($str)
{
    $str = str_replace('  ', ' ',$str);
    $str = str_replace('£', '&pound;', $str);
    return $str;
}

function not_find_user($conn)
{
    $response = array();
    $response['success'] = 'false';
    $response['message'] = 'User email or password is wrong. Please check.';
    $response['user_id'] = '';
    $response['username'] = '';
    $response['email'] = '';
    $response['telephone'] = '';
    $response['user_type'] = '';
    $response['bars'] = array();
    echo json_encode($response);
    mysqli_close($conn);
}

function get_distance_between_points($latitude1, $longitude1, $latitude2, $longitude2)
{
    $theta = $longitude1 - $longitude2;
    $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
    $miles = acos($miles);
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;
    $feet = $miles * 5280;
    $yards = $feet / 3;
    $kilometers = $miles * 1.609344;
    $meters = $kilometers * 1000;

    return compact('kilometers');
}

function GetDrivingDistance($lat1, $lat2, $long1, $long2)
{
    $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$lat1.','.$long1.'&destinations='.$lat2.','.$long2.'&mode=driving&language=pl-PL';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_a = json_decode($response, true);
    $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
    $time = $response_a['rows'][0]['elements'][0]['duration']['text'];

    return array('distance' => $dist, 'time' => $time);
}

function distance($lat1, $lon1, $lat2, $lon2, $unit)
{
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == 'K') {
        return $miles * 1.609344;
    } elseif ($unit == 'N') {
        return $miles * 0.8684;
    } else {
        return $miles;
    }
}

function base64_encode_image($filename = string, $filetype = string)
{
    if ($filename) {
        $imgbinary = fread(fopen($filename, 'r'), filesize($filename));

        return base64_encode($imgbinary);

        //return 'data:image/' . $filetype . ';base64,' . base64_encode($imgbinary);
    }
}

function getCoordinates($address)
{
    $address = str_replace(' ', '+', $address); // replace all the white space with "+" sign to match with google search pattern

    $url = "https://maps.google.com/maps/api/geocode/json?sensor=false&key=AIzaSyDsNbQkYAXhWVUoFi4J_2nD5aRj8B9TvY4&address=$address";

    $response = file_get_contents($url);

    $json = json_decode($response, true); //generate array object from the response from the web
echo($json);

return $json;

    return $json['results'][0]['geometry']['location']['lat'].','.$json['results'][0]['geometry']['location']['lng'];
}
