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

$json_data = json_decode(file_get_contents('php://input'), true);
if (isset($json_data)) {
    $user_id = $json_data['user_id'];
    $user_type = $json_data['user_type'];
    $social_account = $json_data['social_account'];
    $username = $json_data['username'];
    $password = md5($json_data['password']);
    $lat = $json_data['lat'];
    $lon = $json_data['lon'];
} elseif (isset($_REQUEST['username'])) {
    $user_id = $_REQUEST['user_id'];
    $user_type = $_REQUEST['user_type'];
    $social_account = $_REQUEST['social_account'];
    $username = $_REQUEST['username'];
    $password = md5($_REQUEST['password']);
    $lat = $_REQUEST['lat'];
    $lon = $_REQUEST['lon'];
} else {
    $response['success'] = 'false';
    $response['message'] = 'Required field(s) is(are) missing.';
    echo json_encode($response);
    mysqli_close($conn);
    exit();
}

if ($user_id != 0) { // Auto Login
    if ($user_type == 1) { // Customer
        $check_customer = mysqli_query($conn, "SELECT * FROM customer_user WHERE id='$user_id'");
        if (!$check_customer) {
            not_find_user($conn);

            return;
        }
        if (mysqli_num_rows($check_customer) == 0) { // None exist
            not_find_user($conn);

            return;
        }
    } else {
        $check_business = mysqli_query($conn, "SELECT * FROM business_user WHERE id='$user_id'");
        if (!$check_business) {
            not_find_user($conn);

            return;
        }
        if (mysqli_num_rows($check_business) == 0) { // None exist
            not_find_user($conn);

            return;
        }
    }
} else { // Normal Login
    if ($social_account != 0) {
        $check_customer = mysqli_query($conn, "SELECT * FROM customer_user WHERE (email = '$username' or user_name = '$username') and social_account = '$social_account'");
        if (!$check_customer || mysqli_num_rows($check_customer) == 0) {
            not_find_user($conn);

            return;
        } else {
            $user = mysqli_fetch_array($check_customer);
            $user_id = $user['Id'];
            $user_type = '1';
        }
    } else {
        $check_customer = mysqli_query($conn, "SELECT * FROM customer_user WHERE (email = '$username' or user_name = '$username') and password = '$password'");
        if (!$check_customer || mysqli_num_rows($check_customer) == 0) {
            $check_business = mysqli_query($conn, "SELECT * FROM business_user WHERE (email = '$username' or login_name = '$username') /* and reg_flag = 0*/ and password = '$password'");
            if (!$check_business || mysqli_num_rows($check_business) == 0) {
                not_find_user($conn);

                return;
            } else {
                $user = mysqli_fetch_array($check_business);
                $user_id = $user['Id'];

                $user_type = '2';
            }
        } else {
            $user = mysqli_fetch_array($check_customer);
            $user_id = $user['Id'];
            $user_type = '1';
        }
    }
}

if ($user_type == '1') {
    $check_customer = mysqli_query($conn, "SELECT * FROM customer_user WHERE id='$user_id'");
    $row = mysqli_fetch_array($check_customer);

    $response['user_id'] = $row['Id'];
    $response['username'] = $row['username'];
    $response['user_name'] = $row['user_name'];
    $response['gender'] = $row['gender'];
    $response['birthday'] = $row['birthday'];
    $response['email'] = $row['email'];
    $user_id = $row['Id'];
    $response['user_type'] = '1';
    $current_date = date('Y-m-d');
    $duration_check = false;
    $claimed_check = false;
    $response['bars'] = array();

    $get_bar = mysqli_query($conn, "SELECT Id,name,post_code,service_name,address,telephone,website,email,description,music_type,latitude,longitude,category,approve_flag,( 6371 * acos ( cos ( radians('$lat') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('$lon') ) + sin ( radians('$lat') ) * sin( radians( latitude ) ) )) AS distance FROM bar HAVING distance < 10000000 ORDER BY distance asc");
    while ($bar_row = mysqli_fetch_array($get_bar)) {
        $sel_bar_id = $bar_row['Id'];
        $select_deal_bar = mysqli_query($conn, "select * from deal where bar_id='$sel_bar_id'");

        while ($deal_row = mysqli_fetch_array($select_deal_bar)) {
            $duration = $deal_row['duration'];
            $duration_time = DateTime::createFromFormat('d M Y', $duration);
            $database_date = date_format($duration_time, 'Y-m-d');
            $difference = strtotime($database_date) - strtotime($current_date);
            if ($difference < 0) {
            } else {
                $deal_id = $deal_row['Id'];
                $qty = $deal_row['qty'];
                $claimcount_res = mysqli_query($conn, "select count(Id) as claim_count from claim_deal where deal_id='$deal_id'");
                $claimcount_row = mysqli_fetch_array($claimcount_res);
                $claim_count = $claimcount_row['claim_count'];
                $qty_c_value = $qty - $claim_count;
                if ($qty_c_value > 0) {
                    $sel_bar_id = $bar_row['Id'];
                    $select_deal_bar1 = mysqli_query($conn, "select * from deal where bar_id='$sel_bar_id'");
                    if (mysqli_num_rows($select_deal_bar1) > 0) {
                        $bar_list = array();
                        $sel_bar_id = $bar_row['Id'];
                        $bar_list['bar_id'] = $bar_row['Id'];
                        $bar_list['business_name'] = conv_str($bar_row['name']);
                        $bar_list['post_code'] = $bar_row['post_code'];
                        $bar_list['address'] = conv_str($bar_row['address']);
                        $bar_list['service_name'] = $bar_row['service_name'];
                        $bar_list['telephone'] = $bar_row['telephone'];
                        $bar_list['website'] = $bar_row['website'];
                        $bar_list['email'] = $bar_row['email'];
                        $bar_list['description'] = conv_str($bar_row['description']);
                        $bar_list['music_type'] = conv_str($bar_row['music_type']);
                        $bar_list['lat'] = $bar_row['latitude'];
                        $bar_list['lon'] = $bar_row['longitude'];
                        $bar_list['distance'] = $bar_row['distance'];
                        $bar_list['category'] = $bar_row['category'];
                        $bar_list['approve_flag'] = $bar_row['approve_flag'];
                        $bar_list['open_time'] = array();

                        $check_opentime = mysqli_query($conn, "select * from bar_open_time where bar_id='$sel_bar_id'");
                        if (mysqli_num_rows($check_opentime) > 0) {
                            while ($baropentime_row = mysqli_fetch_array($check_opentime)) {
                                $bar_list['open_time']['mon_start'] = $baropentime_row['mon_start'];
                                $bar_list['open_time']['mon_end'] = $baropentime_row['mon_end'];
                                $bar_list['open_time']['tue_start'] = $baropentime_row['tue_start'];
                                $bar_list['open_time']['tue_end'] = $baropentime_row['tue_end'];
                                $bar_list['open_time']['wed_start'] = $baropentime_row['wed_start'];
                                $bar_list['open_time']['wed_end'] = $baropentime_row['wed_end'];
                                $bar_list['open_time']['thur_start'] = $baropentime_row['thur_start'];
                                $bar_list['open_time']['thur_end'] = $baropentime_row['thur_end'];
                                $bar_list['open_time']['fri_start'] = $baropentime_row['fri_start'];
                                $bar_list['open_time']['fri_end'] = $baropentime_row['fri_end'];
                                $bar_list['open_time']['sat_start'] = $baropentime_row['sat_start'];
                                $bar_list['open_time']['sat_end'] = $baropentime_row['sat_end'];
                                $bar_list['open_time']['sun_start'] = $baropentime_row['sun_start'];
                                $bar_list['open_time']['sun_end'] = $baropentime_row['sun_end'];
                            }
                        } else {
                            $bar_list['open_time']['mon_start'] = '';
                            $bar_list['open_time']['mon_end'] = '';
                            $bar_list['open_time']['tue_start'] = '';
                            $bar_list['open_time']['tue_end'] = '';
                            $bar_list['open_time']['wed_start'] = '';
                            $bar_list['open_time']['wed_end'] = '';
                            $bar_list['open_time']['thur_start'] = '';
                            $bar_list['open_time']['thur_end'] = '';
                            $bar_list['open_time']['fri_start'] = '';
                            $bar_list['open_time']['fri_end'] = '';
                            $bar_list['open_time']['sat_start'] = '';
                            $bar_list['open_time']['sat_end'] = '';
                            $bar_list['open_time']['sun_start'] = '';
                            $bar_list['open_time']['sun_end'] = '';
                        }

                        $bar_list['gallery'] = array();
                        $check_background = mysqli_query($conn, "select * from bar_gallery where bar_id='$sel_bar_id'");
                        if (mysqli_num_rows($check_background) > 0) {
                            while ($gallery_row = mysqli_fetch_array($check_background)) {
                                $bar_list['gallery']['background1'] = $gallery_row['background_1'];
                                $bar_list['gallery']['background2'] = $gallery_row['background_2'];
                                $bar_list['gallery']['background3'] = $gallery_row['background_3'];
                                $bar_list['gallery']['background4'] = $gallery_row['background_4'];
                                $bar_list['gallery']['background5'] = $gallery_row['background_5'];
                                $bar_list['gallery']['background6'] = $gallery_row['background_6'];

                                if ($gallery_row['background_1'] != '') {
                                    $imgpath = '../../upload/'.$gallery_row['background_1'];
                                    if (file_exists($imgpath) && filesize($imgpath) > 100) {
                                        list($width, $height, $type, $attr) = getimagesize($imgpath);
                                        if ($width > 0) {
                                            $rate = $height / $width;
                                        } else {
                                            $rate = 0;
                                        }
                                    } else {
                                        $rate = 0;
                                    }
                                    $bar_list['gallery']['height1'] = ''.$rate.'';
                                } else {
                                    $bar_list['gallery']['height1'] = '0';
                                }
                                if ($gallery_row['background_2'] != '') {
                                    $imgpath = '../../upload/'.$gallery_row['background_2'];
                                    if (file_exists($imgpath) && filesize($imgpath) > 100) {
                                        list($width, $height, $type, $attr) = getimagesize($imgpath);
                                        if ($width > 0) {
                                            $rate = $height / $width;
                                        } else {
                                            $rate = 0;
                                        }
                                    } else {
                                        $rate = 0;
                                    }
                                    $bar_list['gallery']['height2'] = ''.$rate.'';
                                } else {
                                    $bar_list['gallery']['height2'] = '0';
                                }
                                if ($gallery_row['background_3'] != '') {
                                    $imgpath = '../../upload/'.$gallery_row['background_3'];
                                    if (file_exists($imgpath) && filesize($imgpath) > 100) {
                                        list($width, $height, $type, $attr) = getimagesize($imgpath);
                                        if ($width > 0) {
                                            $rate = $height / $width;
                                        } else {
                                            $rate = 0;
                                        }
                                    } else {
                                        $rate = 0;
                                    }
                                    $bar_list['gallery']['height3'] = ''.$rate.'';
                                } else {
                                    $bar_list['gallery']['height3'] = '0';
                                }
                                if ($gallery_row['background_4'] != '') {
                                    $imgpath = '../../upload/'.$gallery_row['background_4'];
                                    if (file_exists($imgpath) && filesize($imgpath) > 100) {
                                        list($width, $height, $type, $attr) = getimagesize($imgpath);
                                        if ($width > 0) {
                                            $rate = $height / $width;
                                        } else {
                                            $rate = 0;
                                        }
                                    } else {
                                        $rate = 0;
                                    }
                                    $bar_list['gallery']['height4'] = ''.$rate.'';
                                } else {
                                    $bar_list['gallery']['height4'] = '0';
                                }
                                if ($gallery_row['background_5'] != '') {
                                    $imgpath = '../../upload/'.$gallery_row['background_5'];
                                    if (file_exists($imgpath) && filesize($imgpath) > 100) {
                                        list($width, $height, $type, $attr) = getimagesize($imgpath);
                                        if ($width > 0) {
                                            $rate = $height / $width;
                                        } else {
                                            $rate = 0;
                                        }
                                    } else {
                                        $rate = 0;
                                    }
                                    $bar_list['gallery']['height5'] = ''.$rate.'';
                                } else {
                                    $bar_list['gallery']['height5'] = '0';
                                }
                                if ($gallery_row['background_6'] != '') {
                                    $imgpath = '../../upload/'.$gallery_row['background_6'];
                                    if (file_exists($imgpath) && filesize($imgpath) > 100) {
                                        list($width, $height, $type, $attr) = getimagesize($imgpath);
                                        if ($width > 0) {
                                            $rate = $height / $width;
                                        } else {
                                            $rate = 0;
                                        }
                                    } else {
                                        $rate = 0;
                                    }
                                    $bar_list['gallery']['height6'] = ''.$rate.'';
                                } else {
                                    $bar_list['gallery']['height6'] = '0';
                                }
                            }
                        } else {
                            $bar_list['gallery']['background1'] = '';
                            $bar_list['gallery']['background2'] = '';
                            $bar_list['gallery']['background3'] = '';
                            $bar_list['gallery']['background4'] = '';
                            $bar_list['gallery']['background5'] = '';
                            $bar_list['gallery']['background6'] = '';

                            $bar_list['gallery']['height1'] = '0';
                            $bar_list['gallery']['height2'] = '0';
                            $bar_list['gallery']['height3'] = '0';
                            $bar_list['gallery']['height4'] = '0';
                            $bar_list['gallery']['height5'] = '0';
                            $bar_list['gallery']['height6'] = '0';
                        }

                        $bar_list['deals'] = array();
                        $select_deal_bar = mysqli_query($conn, "select * from deal where bar_id='$sel_bar_id'");
                        while ($dealbar_row = mysqli_fetch_array($select_deal_bar)) {
                            $deal_id = $dealbar_row['Id'];
                            $duration = $dealbar_row['duration'];
                            $duration_time = DateTime::createFromFormat('d M Y', $duration);
                            $database_date = date_format($duration_time, 'Y-m-d');
                            $difference = strtotime($database_date) - strtotime($current_date);

                            if ($difference >= 0) {
                                $qty = $dealbar_row['qty'];
                                $claimcount_res = mysqli_query($conn, "select count(Id) as claim_count from claim_deal where deal_id='$deal_id'");
                                $claimcount_row = mysqli_fetch_array($claimcount_res);
                                $claim_count = $claimcount_row['claim_count'];
                                $qty_c_value = $qty - $claim_count;
                                if ($qty_c_value > 0) {
                                    $dealbarsarray = array();
                                    $dealbarsarray['deal_id'] = $deal_id;
                                    $dealbarsarray['title'] = conv_str($dealbar_row['title']);
                                    $dealbarsarray['description'] = conv_str($dealbar_row['description']);
                                    $dealbarsarray['duration'] = $dealbar_row['duration'];
                                    $dealbarsarray['qty'] = $dealbar_row['qty'];
                                    $count_res = mysqli_query($conn, "select count(Id) as count_wallet from in_wallet where deal_id='$deal_id'");
                                    if ($count_res) {
                                        $count_row = mysqli_fetch_array($count_res);
                                        $dealbarsarray['in_wallet'] = $count_row['count_wallet'];
                                    } else {
                                        $dealbarsarray['in_wallet'] = '0';
                                    }

                                    $impresscount_res = mysqli_query($conn, "select count(Id) as impress_count from impressions_deal where deal_id='$sel_bar_id'");
                                    if ($impresscount_res) {
                                        $impresscount_row = mysqli_fetch_array($impresscount_res);
                                        $dealbarsarray['impressions'] = $impresscount_row['impress_count'];
                                    } else {
                                        $dealbarsarray['impressions'] = '0';
                                    }

                                    $claimcount_res = mysqli_query($conn, "select count(Id) as claim_count from claim_deal where deal_id='$deal_id'");
                                    if ($claimcount_res) {
                                        $claimcount_row = mysqli_fetch_array($claimcount_res);
                                        $dealbarsarray['claimed'] = $claimcount_row['claim_count'];
                                    } else {
                                        $dealbarsarray['claimed'] = '0';
                                    }

                                    $check_wallet = mysqli_query($conn, "select * from in_wallet where deal_id='$deal_id' and user_id='$user_id'");
                                    if (mysqli_num_rows($check_wallet) > 0) {
                                        $dealbarsarray['wallet_check'] = 'true';
                                    } else {
                                        $dealbarsarray['wallet_check'] = 'false';
                                    }

                                    $check_claim = mysqli_query($conn, "select * from claim_deal where deal_id='$deal_id' and user_id='$user_id'");
                                    $dealbarsarray['claimed_check'] = 'false';
                                    if ($check_claim) {
                                        if (mysqli_num_rows($check_claim) > 0) {
                                            $dealbarsarray['claimed_check'] = 'true';
                                        }
                                    }

                                    $check_impressions = mysqli_query($conn, "select * from impressions_deal where deal_id='$sel_bar_id' and user_id='$user_id'");
                                    $dealbarsarray['impressions_check'] = 'false';
                                    if ($check_impressions) {
                                        if (mysqli_num_rows($check_impressions) > 0) {
                                            $dealbarsarray['impressions_check'] = 'true';
                                        }
                                    }

                                    $dealbarsarray['deal_days'] = array();
                                    $select_deal_days = mysqli_query($conn, "select * from deal_days where deal_id='$deal_id'");
                                    while ($deal_days_row = mysqli_fetch_array($select_deal_days)) {
                                        $monday_value = $deal_days_row['monday'];
                                        if ($monday_value == 0) {
                                            $monday = 'false';
                                        } else {
                                            $monday = 'true';
                                        }
                                        $tuesday_value = $deal_days_row['tuesday'];
                                        if ($tuesday_value == 0) {
                                            $tuesday = 'false';
                                        } else {
                                            $tuesday = 'true';
                                        }
                                        $thursday_value = $deal_days_row['thursday'];
                                        if ($thursday_value == 0) {
                                            $thursday = 'false';
                                        } else {
                                            $thursday = 'true';
                                        }
                                        $wednesday_value = $deal_days_row['wednesday'];
                                        if ($wednesday_value == 0) {
                                            $wednesday = 'false';
                                        } else {
                                            $wednesday = 'true';
                                        }
                                        $friday_value = $deal_days_row['friday'];
                                        if ($friday_value == 0) {
                                            $friday = 'false';
                                        } else {
                                            $friday = 'true';
                                        }
                                        $saturday_value = $deal_days_row['saturday'];
                                        if ($saturday_value == 0) {
                                            $saturday = 'false';
                                        } else {
                                            $saturday = 'true';
                                        }
                                        $sunday_value = $deal_days_row['sunday'];
                                        if ($sunday_value == 0) {
                                            $sunday = 'false';
                                        } else {
                                            $sunday = 'true';
                                        }
                                        $dealbarsarray['deal_days']['monday'] = $monday;
                                        $dealbarsarray['deal_days']['tuesday'] = $tuesday;
                                        $dealbarsarray['deal_days']['wednesday'] = $wednesday;
                                        $dealbarsarray['deal_days']['thursday'] = $thursday;
                                        $dealbarsarray['deal_days']['friday'] = $friday;
                                        $dealbarsarray['deal_days']['saturday'] = $saturday;
                                        $dealbarsarray['deal_days']['sunday'] = $sunday;
                                    }
                                    array_push($bar_list['deals'], $dealbarsarray);
                                }
                            }
                        }

                        array_push($response['bars'], $bar_list);
                    }
                }
            }
        }
    }

    $response['wallets'] = array();

    $check_wallets = mysqli_query($conn, "select * from in_wallet where user_id = '$user_id'");
    if (mysqli_num_rows($check_wallets) > 0) {
        while ($wallet_row = mysqli_fetch_array($check_wallets)) {
            $wallet_bar_id = $wallet_row['bar_id'];
            $walletdeal_id = $wallet_row['deal_id'];
            $get_wallet_bar_res = mysqli_query($conn, "SELECT Id,name,service_name,post_code,address,telephone,website,email,description,music_type,latitude,longitude,category,approve_flag,( 6371 * acos ( cos ( radians('$lat') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('$lon') ) + sin ( radians('$lat') ) * sin( radians( latitude ) ) )) AS distance FROM bar where Id='$wallet_bar_id' HAVING distance < 10000000 ORDER BY distance asc");
            $get_wallet_bar_row = mysqli_fetch_array($get_wallet_bar_res);

            $check_wallet_deal_res = mysqli_query($conn, "select * from in_wallet where user_id = '$user_id' and deal_id='$walletdeal_id' ");
            while ($wallet_deal_row = mysqli_fetch_array($check_wallet_deal_res)) {
                $wallet_deal_id = $wallet_deal_row['deal_id'];

                $get_wallet_deal_res = mysqli_query($conn, "select * from deal where Id='$wallet_deal_id'");
                $wallet_Deal_row = mysqli_fetch_array($get_wallet_deal_res);

                $duration = $wallet_Deal_row['duration'];
                $duration_time = DateTime::createFromFormat('d M Y', $duration);
                $database_date = date_format($duration_time, 'Y-m-d');
                $difference = strtotime($database_date) - strtotime($current_date);

                if ($difference >= 0) {
                    $qty = $wallet_Deal_row['qty'];
                    $claimcount_res = mysqli_query($conn, "select count(Id) as claim_count from claim_deal where deal_id='$wallet_deal_id'");
                    $claimcount_row = mysqli_fetch_array($claimcount_res);
                    $claim_count = $claimcount_row['claim_count'];
                    $qty_c_value = $qty - $claim_count;

                    if ($qty_c_value > 0) {
                        $check_claim = mysqli_query($conn, "select * from claim_deal where deal_id ='$wallet_deal_id' and user_id='$user_id'");
                        if (mysqli_num_rows($check_claim) == 0) {
                            $wallet_bar = array();
                            $sel_bar_id = $get_wallet_bar_row['Id'];
                            $wallet_bar['bar_id'] = $get_wallet_bar_row['Id'];
                            $wallet_bar['business_name'] = conv_str($get_wallet_bar_row['name']);
                            $wallet_bar['post_code'] = $get_wallet_bar_row['post_code'];
                            $wallet_bar['address'] = conv_str($get_wallet_bar_row['address']);
                            $wallet_bar['service_name'] = conv_str($get_wallet_bar_row['service_name']);
                            $wallet_bar['telephone'] = $get_wallet_bar_row['telephone'];
                            $wallet_bar['website'] = $get_wallet_bar_row['website'];
                            $wallet_bar['email'] = $get_wallet_bar_row['email'];
                            $wallet_bar['description'] = conv_str($get_wallet_bar_row['description']);
                            $wallet_bar['music_type'] = conv_str($get_wallet_bar_row['music_type']);
                            $wallet_bar['lat'] = $get_wallet_bar_row['latitude'];
                            $wallet_bar['lon'] = $get_wallet_bar_row['longitude'];
                            $wallet_bar['distance'] = $get_wallet_bar_row['distance'];
                            $wallet_bar['category'] = $get_wallet_bar_row['category'];
                            $wallet_bar['approve_flag'] = $get_wallet_bar_row['approve_flag'];
                            $wallet_deal = array();

                            $wallet_bar['open_time'] = array();
                            $check_opentime = mysqli_query($conn, "select * from bar_open_time where bar_id='$sel_bar_id'");
                            if (mysqli_num_rows($check_opentime) > 0) {
                                while ($baropentime_row = mysqli_fetch_array($check_opentime)) {
                                    $wallet_bar['open_time']['mon_start'] = $baropentime_row['mon_start'];
                                    $wallet_bar['open_time']['mon_end'] = $baropentime_row['mon_end'];
                                    $wallet_bar['open_time']['tue_start'] = $baropentime_row['tue_start'];
                                    $wallet_bar['open_time']['tue_end'] = $baropentime_row['tue_end'];
                                    $wallet_bar['open_time']['wed_start'] = $baropentime_row['wed_start'];
                                    $wallet_bar['open_time']['wed_end'] = $baropentime_row['wed_end'];
                                    $wallet_bar['open_time']['thur_start'] = $baropentime_row['thur_start'];
                                    $wallet_bar['open_time']['thur_end'] = $baropentime_row['thur_end'];
                                    $wallet_bar['open_time']['fri_start'] = $baropentime_row['fri_start'];
                                    $wallet_bar['open_time']['fri_end'] = $baropentime_row['fri_end'];
                                    $wallet_bar['open_time']['sat_start'] = $baropentime_row['sat_start'];
                                    $wallet_bar['open_time']['sat_end'] = $baropentime_row['sat_end'];
                                    $wallet_bar['open_time']['sun_start'] = $baropentime_row['sun_start'];
                                    $wallet_bar['open_time']['sun_end'] = $baropentime_row['sun_end'];
                                }
                            } else {
                                $wallet_bar['open_time']['mon_start'] = '';
                                $wallet_bar['open_time']['mon_end'] = '';
                                $wallet_bar['open_time']['tue_start'] = '';
                                $wallet_bar['open_time']['tue_end'] = '';
                                $wallet_bar['open_time']['wed_start'] = '';
                                $wallet_bar['open_time']['wed_end'] = '';
                                $wallet_bar['open_time']['thur_start'] = '';
                                $wallet_bar['open_time']['thur_end'] = '';
                                $wallet_bar['open_time']['fri_start'] = '';
                                $wallet_bar['open_time']['fri_end'] = '';
                                $wallet_bar['open_time']['sat_start'] = '';
                                $wallet_bar['open_time']['sat_end'] = '';
                                $wallet_bar['open_time']['sun_start'] = '';
                                $wallet_bar['open_time']['sun_end'] = '';
                            }

                            $wallet_bar['gallery'] = array();
                            $check_background = mysqli_query($conn, "select * from bar_gallery where bar_id='$sel_bar_id'");
                            if (mysqli_num_rows($check_background) > 0) {
                                while ($gallery_row = mysqli_fetch_array($check_background)) {
                                    $wallet_bar['gallery']['background1'] = $gallery_row['background_1'];
                                    $wallet_bar['gallery']['background2'] = $gallery_row['background_2'];
                                    $wallet_bar['gallery']['background3'] = $gallery_row['background_3'];
                                    $wallet_bar['gallery']['background4'] = $gallery_row['background_4'];
                                    $wallet_bar['gallery']['background5'] = $gallery_row['background_5'];
                                    $wallet_bar['gallery']['background6'] = $gallery_row['background_6'];

                                    if ($gallery_row['background_1'] != '') {
                                        $imgpath = '../../upload/'.$gallery_row['background_1'];
                                        if (file_exists($imgpath) && filesize($imgpath) > 100) {
                                            list($width, $height, $type, $attr) = getimagesize($imgpath);
                                            if ($width > 0) {
                                                $rate = $height / $width;
                                            } else {
                                                $rate = 0;
                                            }
                                        } else {
                                            $rate = 0;
                                        }
                                        $wallet_bar['gallery']['height1'] = ''.$rate.'';
                                    } else {
                                        $wallet_bar['gallery']['height1'] = '0';
                                    }
                                    if ($gallery_row['background_2'] != '') {
                                        $imgpath = '../../upload/'.$gallery_row['background_2'];
                                        if (file_exists($imgpath) && filesize($imgpath) > 100) {
                                            list($width, $height, $type, $attr) = getimagesize($imgpath);
                                            if ($width > 0) {
                                                $rate = $height / $width;
                                            } else {
                                                $rate = 0;
                                            }
                                        } else {
                                            $rate = 0;
                                        }
                                        $wallet_bar['gallery']['height2'] = ''.$rate.'';
                                    } else {
                                        $wallet_bar['gallery']['height2'] = '0';
                                    }
                                    if ($gallery_row['background_3'] != '') {
                                        $imgpath = '../../upload/'.$gallery_row['background_3'];
                                        if (file_exists($imgpath) && filesize($imgpath) > 100) {
                                            list($width, $height, $type, $attr) = getimagesize($imgpath);
                                            if ($width > 0) {
                                                $rate = $height / $width;
                                            } else {
                                                $rate = 0;
                                            }
                                        } else {
                                            $rate = 0;
                                        }
                                        $wallet_bar['gallery']['height3'] = ''.$rate.'';
                                    } else {
                                        $wallet_bar['gallery']['height3'] = '0';
                                    }
                                    if ($gallery_row['background_4'] != '') {
                                        $imgpath = '../../upload/'.$gallery_row['background_4'];
                                        if (file_exists($imgpath) && filesize($imgpath) > 100) {
                                            list($width, $height, $type, $attr) = getimagesize($imgpath);
                                            if ($width > 0) {
                                                $rate = $height / $width;
                                            } else {
                                                $rate = 0;
                                            }
                                        } else {
                                            $rate = 0;
                                        }
                                        $wallet_bar['gallery']['height4'] = ''.$rate.'';
                                    } else {
                                        $wallet_bar['gallery']['height4'] = '0';
                                    }
                                    if ($gallery_row['background_5'] != '') {
                                        $imgpath = '../../upload/'.$gallery_row['background_5'];
                                        if (file_exists($imgpath) && filesize($imgpath) > 100) {
                                            list($width, $height, $type, $attr) = getimagesize($imgpath);
                                            if ($width > 0) {
                                                $rate = $height / $width;
                                            } else {
                                                $rate = 0;
                                            }
                                        } else {
                                            $rate = 0;
                                        }
                                        $wallet_bar['gallery']['height5'] = ''.$rate.'';
                                    } else {
                                        $wallet_bar['gallery']['height5'] = '0';
                                    }
                                    if ($gallery_row['background_6'] != '') {
                                        $imgpath = '../../upload/'.$gallery_row['background_6'];
                                        if (file_exists($imgpath) && filesize($imgpath) > 100) {
                                            list($width, $height, $type, $attr) = getimagesize($imgpath);
                                            if ($width > 0) {
                                                $rate = $height / $width;
                                            } else {
                                                $rate = 0;
                                            }
                                        } else {
                                            $rate = 0;
                                        }
                                        $wallet_bar['gallery']['height6'] = ''.$rate.'';
                                    } else {
                                        $wallet_bar['gallery']['height6'] = '0';
                                    }
                                }
                            } else {
                                $wallet_bar['gallery']['background1'] = '';
                                $wallet_bar['gallery']['background2'] = '';
                                $wallet_bar['gallery']['background3'] = '';
                                $wallet_bar['gallery']['background4'] = '';
                                $wallet_bar['gallery']['background5'] = '';
                                $wallet_bar['gallery']['background6'] = '';

                                $wallet_bar['gallery']['height1'] = '0';
                                $wallet_bar['gallery']['height2'] = '0';
                                $wallet_bar['gallery']['height3'] = '0';
                                $wallet_bar['gallery']['height4'] = '0';
                                $wallet_bar['gallery']['height5'] = '0';
                                $wallet_bar['gallery']['height6'] = '0';
                            }

                            $wallet_deal = array();

                            $get_wallet_dealbar_res = mysqli_query($conn, "select * from in_wallet where user_id='$user_id' and deal_id='$wallet_deal_id'");
                            while ($get_wallet_dealbar_row = mysqli_fetch_array($get_wallet_dealbar_res)) {
                                $select_wallet_deal_id = $get_wallet_dealbar_row['deal_id'];
                                $select_dealbar_res = mysqli_query($conn, "select * from deal where Id= '$select_wallet_deal_id'");
                                $select_dealbar_row = mysqli_fetch_array($select_dealbar_res);

                                $duration = $select_dealbar_row['duration'];

                                $duration_time = DateTime::createFromFormat('d M Y', $duration);
                                $database_date = date_format($duration_time, 'Y-m-d');
                                $difference = strtotime($database_date) - strtotime($current_date);
                                if ($difference >= 0) {
                                    $qty1 = $select_dealbar_row['qty'];

                                    $claimcount_res = mysqli_query($conn, "select count(Id) as claim_count from claim_deal where deal_id='$select_wallet_deal_id'");
                                    $claimcount_row = mysqli_fetch_array($claimcount_res);
                                    $claim_count1 = $claimcount_row['claim_count'];
                                    $qty_c_value1 = $qty1 - $claim_count1;
                                    if ($qty_c_value1 > 0) {
                                        $deal_id = $select_dealbar_row['Id'];
                                        //$dealbarsarray = array();
                                        $wallet_bar['deal_id'] = $deal_id;
                                        $wallet_bar['title'] = conv_str($select_dealbar_row['title']);
                                        $wallet_bar['description'] = conv_str($select_dealbar_row['description']);
                                        $wallet_bar['duration'] = $select_dealbar_row['duration'];
                                        $wallet_bar['qty'] = $select_dealbar_row['qty'];

                                        $count_res = mysqli_query($conn, "select count(Id) as count_wallet from in_wallet where deal_id='$deal_id'");
                                        if ($count_res) {
                                            $count_row = mysqli_fetch_array($count_res);
                                            $wallet_bar['in_wallet'] = $count_row['count_wallet'];
                                        } else {
                                            $wallet_bar['in_wallet'] = '0';
                                        }

                                        $impresscount_res = mysqli_query($conn, "select count(Id) as impress_count from impressions_deal where deal_id='$deal_id'");
                                        if ($impresscount_res) {
                                            $impresscount_row = mysqli_fetch_array($impresscount_res);
                                            $wallet_bar['impressions'] = $impresscount_row['impress_count'];
                                        } else {
                                            $wallet_bar['impressions'] = '0';
                                        }

                                        $claimcount_res = mysqli_query($conn, "select count(Id) as claim_count from claim_deal where deal_id='$deal_id'");
                                        if ($claimcount_res) {
                                            $claimcount_row = mysqli_fetch_array($claimcount_res);
                                            $wallet_bar['claimed'] = $claimcount_row['claim_count'];
                                        } else {
                                            $wallet_bar['claimed'] = '0';
                                        }

                                        $check_wallet = mysqli_query($conn, "select * from in_wallet where deal_id='$deal_id' and user_id='$user_id'");
                                        $wallet_bar['wallet_check'] = 'false';
                                        if ($check_wallet) {
                                            if (mysqli_num_rows($check_wallet) > 0) {
                                                $wallet_bar['wallet_check'] = 'true';
                                            }
                                        }

                                        $check_claim = mysqli_query($conn, "select * from claim_deal where deal_id='$deal_id' and user_id='$user_id'");
                                        $wallet_bar['claimed_check'] = 'false';
                                        if ($check_claim) {
                                            if (mysqli_num_rows($check_claim) > 0) {
                                                $wallet_bar['claimed_check'] = 'true';
                                            }
                                        }

                                        $check_impressions = mysqli_query($conn, "select * from impressions_deal where deal_id='$deal_id' and user_id='$user_id'");
                                        $wallet_bar['impressions_check'] = 'false';
                                        if ($check_impressions) {
                                            if (mysqli_num_rows($check_impressions) > 0) {
                                                $wallet_bar['impressions_check'] = 'true';
                                            }
                                        }

                                        $wallet_bar['deal_days'] = array();
                                        $select_deal_days = mysqli_query($conn, "select * from deal_days where deal_id='$deal_id'");
                                        while ($deal_days_row = mysqli_fetch_array($select_deal_days)) {
                                            $monday_value = $deal_days_row['monday'];
                                            if ($monday_value == 0) {
                                                $monday = 'false';
                                            } else {
                                                $monday = 'true';
                                            }
                                            $tuesday_value = $deal_days_row['tuesday'];
                                            if ($tuesday_value == 0) {
                                                $tuesday = 'false';
                                            } else {
                                                $tuesday = 'true';
                                            }
                                            $thursday_value = $deal_days_row['thursday'];
                                            if ($thursday_value == 0) {
                                                $thursday = 'false';
                                            } else {
                                                $thursday = 'true';
                                            }
                                            $wednesday_value = $deal_days_row['wednesday'];
                                            if ($wednesday_value == 0) {
                                                $wednesday = 'false';
                                            } else {
                                                $wednesday = 'true';
                                            }
                                            $friday_value = $deal_days_row['friday'];
                                            if ($friday_value == 0) {
                                                $friday = 'false';
                                            } else {
                                                $friday = 'true';
                                            }
                                            $saturday_value = $deal_days_row['saturday'];
                                            if ($saturday_value == 0) {
                                                $saturday = 'false';
                                            } else {
                                                $saturday = 'true';
                                            }
                                            $sunday_value = $deal_days_row['sunday'];
                                            if ($sunday_value == 0) {
                                                $sunday = 'false';
                                            } else {
                                                $sunday = 'true';
                                            }
                                            $wallet_bar['deal_days']['monday'] = $monday;
                                            $wallet_bar['deal_days']['tuesday'] = $tuesday;
                                            $wallet_bar['deal_days']['wednesday'] = $wednesday;
                                            $wallet_bar['deal_days']['thursday'] = $thursday;
                                            $wallet_bar['deal_days']['friday'] = $friday;
                                            $wallet_bar['deal_days']['saturday'] = $saturday;
                                            $wallet_bar['deal_days']['sunday'] = $sunday;
                                        }
                                        //array_push($wallet_bar,$dealbarsarray);
                                    }
                                }
                                array_push($response['wallets'], $wallet_bar);
                            }
                        }
                    }
                }
            }
        }
    }
    $response['success'] = 'true';
} else {
    $check_business = mysqli_query($conn, "SELECT * FROM business_user WHERE Id='$user_id'");
    $row = mysqli_fetch_array($check_business);

    $response['user_id'] = $row['Id'];
    $response['username'] = $row['username'];
    $response['email'] = $row['email'];
    $response['telephone'] = $row['telephone'];
    $response['user_type'] = '2';

    $user_id = $row['Id'];
    $response['bars'] = array();
    $check_bars = mysqli_query($conn, "select * from bar where user_id='$user_id'");
    while ($bar_row = mysqli_fetch_array($check_bars)) {
        $bar_list = array();
        $sel_bar_id = $bar_row['Id'];
        $bar_list['bar_id'] = $bar_row['Id'];
        $bar_list['business_name'] = conv_str($bar_row['name']);
        $bar_list['post_code'] = $bar_row['post_code'];
        $bar_list['address'] = conv_str($bar_row['address']);
        $bar_list['service_name'] = conv_str($bar_row['service_name']);
        $bar_list['telephone'] = $bar_row['telephone'];
        $bar_list['website'] = $bar_row['website'];
        $bar_list['email'] = $bar_row['email'];
        $bar_list['description'] = conv_str($bar_row['description']);
        $bar_list['music_type'] = conv_str($bar_row['music_type']);
        $bar_list['lat'] = $bar_row['latitude'];
        $bar_list['lon'] = $bar_row['longitude'];
        $bar_list['category'] = $bar_row['category'];
        $bar_list['approve_flag'] = $bar_row['approve_flag'];
        $bar_list['open_time'] = array();
        $check_opentime = mysqli_query($conn, "select * from bar_open_time where bar_id='$sel_bar_id'");
        if (mysqli_num_rows($check_opentime) > 0) {
            while ($baropentime_row = mysqli_fetch_array($check_opentime)) {
                $bar_list['open_time']['mon_start'] = $baropentime_row['mon_start'];
                $bar_list['open_time']['mon_end'] = $baropentime_row['mon_end'];
                $bar_list['open_time']['tue_start'] = $baropentime_row['tue_start'];
                $bar_list['open_time']['tue_end'] = $baropentime_row['tue_end'];
                $bar_list['open_time']['wed_start'] = $baropentime_row['wed_start'];
                $bar_list['open_time']['wed_end'] = $baropentime_row['wed_end'];
                $bar_list['open_time']['thur_start'] = $baropentime_row['thur_start'];
                $bar_list['open_time']['thur_end'] = $baropentime_row['thur_end'];
                $bar_list['open_time']['fri_start'] = $baropentime_row['fri_start'];
                $bar_list['open_time']['fri_end'] = $baropentime_row['fri_end'];
                $bar_list['open_time']['sat_start'] = $baropentime_row['sat_start'];
                $bar_list['open_time']['sat_end'] = $baropentime_row['sat_end'];
                $bar_list['open_time']['sun_start'] = $baropentime_row['sun_start'];
                $bar_list['open_time']['sun_end'] = $baropentime_row['sun_end'];
            }
        } else {
            $bar_list['open_time']['mon_start'] = '';
            $bar_list['open_time']['mon_end'] = '';
            $bar_list['open_time']['tue_start'] = '';
            $bar_list['open_time']['tue_end'] = '';
            $bar_list['open_time']['wed_start'] = '';
            $bar_list['open_time']['wed_end'] = '';
            $bar_list['open_time']['thur_start'] = '';
            $bar_list['open_time']['thur_end'] = '';
            $bar_list['open_time']['fri_start'] = '';
            $bar_list['open_time']['fri_end'] = '';
            $bar_list['open_time']['sat_start'] = '';
            $bar_list['open_time']['sat_end'] = '';
            $bar_list['open_time']['sun_start'] = '';
            $bar_list['open_time']['sun_end'] = '';
        }

        $bar_list['gallery'] = array();
        $check_background = mysqli_query($conn, "select * from bar_gallery where bar_id='$sel_bar_id'");
        if (mysqli_num_rows($check_background) > 0) {
            while ($gallery_row = mysqli_fetch_array($check_background)) {
                $bar_list['gallery']['background1'] = $gallery_row['background_1'];
                $bar_list['gallery']['background2'] = $gallery_row['background_2'];
                $bar_list['gallery']['background3'] = $gallery_row['background_3'];
                $bar_list['gallery']['background4'] = $gallery_row['background_4'];
                $bar_list['gallery']['background5'] = $gallery_row['background_5'];
                $bar_list['gallery']['background6'] = $gallery_row['background_6'];

                if ($gallery_row['background_1'] != '') {
                    $imgpath = '../../upload/'.$gallery_row['background_1'];
                    if (file_exists($imgpath) && filesize($imgpath) > 100) {
                        list($width, $height, $type, $attr) = getimagesize($imgpath);
                        if ($width > 0) {
                            $rate = $height / $width;
                        } else {
                            $rate = 0;
                        }
                    } else {
                        $rate = 0;
                    }
                    $bar_list['gallery']['height1'] = ''.$rate.'';
                } else {
                    $bar_list['gallery']['height1'] = '0';
                }
                if ($gallery_row['background_2'] != '') {
                    $imgpath = '../../upload/'.$gallery_row['background_2'];
                    if (file_exists($imgpath) && filesize($imgpath) > 100) {
                        list($width, $height, $type, $attr) = getimagesize($imgpath);
                        if ($width > 0) {
                            $rate = $height / $width;
                        } else {
                            $rate = 0;
                        }
                    } else {
                        $rate = 0;
                    }
                    $bar_list['gallery']['height2'] = ''.$rate.'';
                } else {
                    $bar_list['gallery']['height2'] = '0';
                }
                if ($gallery_row['background_3'] != '') {
                    $imgpath = '../../upload/'.$gallery_row['background_3'];
                    if (file_exists($imgpath) && filesize($imgpath) > 100) {
                        list($width, $height, $type, $attr) = getimagesize($imgpath);
                        if ($width > 0) {
                            $rate = $height / $width;
                        } else {
                            $rate = 0;
                        }
                    } else {
                        $rate = 0;
                    }
                    $bar_list['gallery']['height3'] = ''.$rate.'';
                } else {
                    $bar_list['gallery']['height3'] = '0';
                }
                if ($gallery_row['background_4'] != '') {
                    $imgpath = '../../upload/'.$gallery_row['background_4'];
                    if (file_exists($imgpath) && filesize($imgpath) > 100) {
                        list($width, $height, $type, $attr) = getimagesize($imgpath);
                        if ($width > 0) {
                            $rate = $height / $width;
                        } else {
                            $rate = 0;
                        }
                    } else {
                        $rate = 0;
                    }
                    $bar_list['gallery']['height4'] = ''.$rate.'';
                } else {
                    $bar_list['gallery']['height4'] = '0';
                }
                if ($gallery_row['background_5'] != '') {
                    $imgpath = '../../upload/'.$gallery_row['background_5'];
                    if (file_exists($imgpath) && filesize($imgpath) > 100) {
                        list($width, $height, $type, $attr) = getimagesize($imgpath);
                        if ($width > 0) {
                            $rate = $height / $width;
                        } else {
                            $rate = 0;
                        }
                    } else {
                        $rate = 0;
                    }
                    $bar_list['gallery']['height5'] = ''.$rate.'';
                } else {
                    $bar_list['gallery']['height5'] = '0';
                }
                if ($gallery_row['background_6'] != '') {
                    $imgpath = '../../upload/'.$gallery_row['background_6'];
                    if (file_exists($imgpath) && filesize($imgpath) > 100) {
                        list($width, $height, $type, $attr) = getimagesize($imgpath);
                        if ($width > 0) {
                            $rate = $height / $width;
                        } else {
                            $rate = 0;
                        }
                    } else {
                        $rate = 0;
                    }
                    $bar_list['gallery']['height6'] = ''.$rate.'';
                } else {
                    $bar_list['gallery']['height6'] = '0';
                }
            }
        } else {
            $bar_list['gallery']['background1'] = '';
            $bar_list['gallery']['background2'] = '';
            $bar_list['gallery']['background3'] = '';
            $bar_list['gallery']['background4'] = '';
            $bar_list['gallery']['background5'] = '';
            $bar_list['gallery']['background6'] = '';
            $bar_list['gallery']['height1'] = '0';
            $bar_list['gallery']['height2'] = '0';
            $bar_list['gallery']['height3'] = '0';
            $bar_list['gallery']['height4'] = '0';
            $bar_list['gallery']['height5'] = '0';
            $bar_list['gallery']['height6'] = '0';
        }

        $bar_list['deals'] = array();
        $select_deal_bar = mysqli_query($conn, "select * from deal where bar_id='$sel_bar_id'");
        while ($dealbar_row = mysqli_fetch_array($select_deal_bar)) {
            $dealbarsarray = array();
            $deal_id = $dealbar_row['Id'];
            $dealbarsarray['deal_id'] = $deal_id;
            $dealbarsarray['title'] = conv_str($dealbar_row['title']);
            $dealbarsarray['description'] = conv_str($dealbar_row['description']);
            $dealbarsarray['duration'] = $dealbar_row['duration'];
            $dealbarsarray['qty'] = $dealbar_row['qty'];
            $count_res = mysqli_query($conn, "select count(Id) as count_wallet from in_wallet where deal_id='$deal_id'");
            if ($count_res) {
                $count_row = mysqli_fetch_array($count_res);
                $dealbarsarray['in_wallet'] = $count_row['count_wallet'];
            } else {
                $dealbarsarray['in_wallet'] = '0';
            }

            $impresscount_res = mysqli_query($conn, "select count(Id) as impress_count from impressions_deal where deal_id='$sel_bar_id'");
            if ($impresscount_res) {
                $impresscount_row = mysqli_fetch_array($impresscount_res);
                $dealbarsarray['impressions'] = $impresscount_row['impress_count'];
            } else {
                $dealbarsarray['impressions'] = '0';
            }

            $claimcount_res = mysqli_query($conn, "select count(Id) as claim_count from claim_deal where deal_id='$deal_id'");
            if ($claimcount_res) {
                $claimcount_row = mysqli_fetch_array($claimcount_res);
                $dealbarsarray['claimed'] = $claimcount_row['claim_count'];
            } else {
                $dealbarsarray['claimed'] = '0';
            }

            $check_bar = mysqli_query($conn, "select * from engagement where bar_id='$sel_bar_id'");
            $dealbarsarray['engagement'] = '0';
            if ($check_bar) {
                if (mysqli_num_rows($check_bar) > 0) {
                    $engagement_res = mysqli_query($conn, "select count(Id) as engagement_count from engagement where bar_id='$sel_bar_id'");
                    $engagement_row = mysqli_fetch_array($engagement_res);
                    $dealbarsarray['engagement'] = $engagement_row['engagement_count'];
                }
            }

            $check_bar1 = mysqli_query($conn, "select * from clicks where bar_id='$sel_bar_id'");
            $dealbarsarray['clicks'] = '0';
            if ($check_bar1) {
                if (mysqli_num_rows($check_bar1) > 0) {
                    $clicks_res = mysqli_query($conn, "select count(Id) as engagement_count from clicks where bar_id='$sel_bar_id'");
                    $clicks_row = mysqli_fetch_array($clicks_res);
                    $dealbarsarray['clicks'] = $clicks_row['engagement_count'];
                }
            }

            $dealbarsarray['deal_days'] = array();
            $select_deal_days = mysqli_query($conn, "select * from deal_days where deal_id='$deal_id'");
            while ($deal_days_row = mysqli_fetch_array($select_deal_days)) {
                $monday_value = $deal_days_row['monday'];
                if ($monday_value == 0) {
                    $monday = 'false';
                } else {
                    $monday = 'true';
                }
                $tuesday_value = $deal_days_row['tuesday'];
                if ($tuesday_value == 0) {
                    $tuesday = 'false';
                } else {
                    $tuesday = 'true';
                }
                $thursday_value = $deal_days_row['thursday'];
                if ($thursday_value == 0) {
                    $thursday = 'false';
                } else {
                    $thursday = 'true';
                }
                $wednesday_value = $deal_days_row['wednesday'];
                if ($wednesday_value == 0) {
                    $wednesday = 'false';
                } else {
                    $wednesday = 'true';
                }
                $friday_value = $deal_days_row['friday'];
                if ($friday_value == 0) {
                    $friday = 'false';
                } else {
                    $friday = 'true';
                }
                $saturday_value = $deal_days_row['saturday'];
                if ($saturday_value == 0) {
                    $saturday = 'false';
                } else {
                    $saturday = 'true';
                }
                $sunday_value = $deal_days_row['sunday'];
                if ($sunday_value == 0) {
                    $sunday = 'false';
                } else {
                    $sunday = 'true';
                }
                $dealbarsarray['deal_days']['monday'] = $monday;
                $dealbarsarray['deal_days']['tuesday'] = $tuesday;
                $dealbarsarray['deal_days']['wednesday'] = $wednesday;
                $dealbarsarray['deal_days']['thursday'] = $thursday;
                $dealbarsarray['deal_days']['friday'] = $friday;
                $dealbarsarray['deal_days']['saturday'] = $saturday;
                $dealbarsarray['deal_days']['sunday'] = $sunday;
            }
            array_push($bar_list['deals'], $dealbarsarray);
        }

        array_push($response['bars'], $bar_list);
    }

    // success
    $response['success'] = 'true';
    $response['message'] = 'Welcome '.$row['username'].'.';
}

echo json_encode($response);
mysqli_close($conn);
