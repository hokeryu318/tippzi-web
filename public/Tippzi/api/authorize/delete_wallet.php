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
    $deal_id = $json_data['deal_id'];
    $bar_id = $json_data['bar_id'];
    $lat = $json_data['lat'];
    $lon = $json_data['lon'];
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

$check_wallet = mysqli_query($conn, "select * from in_wallet where user_id='$user_id' and bar_id='$bar_id' and deal_id='$deal_id'");
if (mysqli_num_rows($check_wallet) > 0) {
    $wallert_delete = mysqli_query($conn, "delete from in_wallet where user_id='$user_id' and bar_id='$bar_id' and deal_id='$deal_id'");
    if ($wallert_delete) {
        $result_check_existing_user = mysqli_query($conn, "SELECT * FROM customer_user WHERE Id='$user_id'") or die(mysql_error());

        if (mysqli_num_rows($result_check_existing_user) > 0) {
            $row = mysqli_fetch_array($result_check_existing_user);
            $response['user_id'] = $row['Id'];
            $response['username'] = $row['username'];
            $response['user_name'] = $row['user_name'];
            $response['gender'] = $row['gender'];
            $response['birthday'] = $row['birthday'];
            $response['email'] = $row['email'];
            $response['user_type'] = '1';

            $current_date = date('Y-m-d');
            $duration_check = false;
            $claimed_check = false;
            $response['bars'] = array();
            $get_bar = mysqli_query($conn, "SELECT Id,name,service_name,post_code,address,telephone,website,email,description,music_type,latitude,longitude,category,approve_flag,( 6371 * acos ( cos ( radians('$lat') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('$lon') ) + sin ( radians('$lat') ) * sin( radians( latitude ) ) )) AS distance FROM bar HAVING distance < 10000000 ORDER BY distance asc");
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
                                //							$check_bars = mysqli_query($conn, "SELECT Id,name,post_code,address,telephone,website,email,description,music_type,latitude,longitude,( 6371 * acos ( cos ( radians('$lat') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('$lon') ) + sin ( radians('$lat') ) * sin( radians( latitude ) ) )) AS distance FROM bar HAVING distance < 10000000 ORDER BY distance asc");
                                //							while($bar_row = mysqli_fetch_array($check_bars)) {
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
                                            $count_row = mysqli_fetch_array($count_res);
                                            $dealbarsarray['in_wallet'] = $count_row['count_wallet'];

                                            $impresscount_res = mysqli_query($conn, "select count(Id) as impress_count from impressions_deal where deal_id='$sel_bar_id'");
                                            $impresscount_row = mysqli_fetch_array($impresscount_res);
                                            $dealbarsarray['impressions'] = $impresscount_row['impress_count'];

                                            $claimcount_res = mysqli_query($conn, "select count(Id) as claim_count from claim_deal where deal_id='$deal_id'");
                                            $claimcount_row = mysqli_fetch_array($claimcount_res);
                                            $dealbarsarray['claimed'] = $claimcount_row['claim_count'];

                                            $check_wallet = mysqli_query($conn, "select * from in_wallet where deal_id='$deal_id' and user_id='$user_id'");
                                            if (mysqli_num_rows($check_wallet) > 0) {
                                                $dealbarsarray['wallet_check'] = 'true';
                                            } else {
                                                $dealbarsarray['wallet_check'] = 'false';
                                            }

                                            $check_claim = mysqli_query($conn, "select * from claim_deal where deal_id='$deal_id' and user_id='$user_id'");
                                            if (mysqli_num_rows($check_claim) > 0) {
                                                $dealbarsarray['claimed_check'] = 'true';
                                            } else {
                                                $dealbarsarray['claimed_check'] = 'false';
                                            }

                                            $check_impressions = mysqli_query($conn, "select * from impressions_deal where deal_id='$sel_bar_id' and user_id='$user_id'");
                                            if (mysqli_num_rows($check_impressions) > 0) {
                                                $dealbarsarray['impressions_check'] = 'true';
                                            } else {
                                                $dealbarsarray['impressions_check'] = 'false';
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
                                //}
                            }
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
                $get_wallet_bar_res = mysqli_query($conn, "SELECT Id,service_name,name,post_code,address,telephone,website,email,description,music_type,latitude,longitude,category,approve_flag,( 6371 * acos ( cos ( radians('$lat') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('$lon') ) + sin ( radians('$lat') ) * sin( radians( latitude ) ) )) AS distance FROM bar where Id='$wallet_bar_id' HAVING distance < 10000000 ORDER BY distance asc");
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
                                            $count_row = mysqli_fetch_array($count_res);
                                            $wallet_bar['in_wallet'] = $count_row['count_wallet'];

                                            $impresscount_res = mysqli_query($conn, "select count(Id) as impress_count from impressions_deal where deal_id='$deal_id'");
                                            $impresscount_row = mysqli_fetch_array($impresscount_res);
                                            $wallet_bar['impressions'] = $impresscount_row['impress_count'];

                                            $claimcount_res = mysqli_query($conn, "select count(Id) as claim_count from claim_deal where deal_id='$deal_id'");
                                            $claimcount_row = mysqli_fetch_array($claimcount_res);
                                            $wallet_bar['claimed'] = $claimcount_row['claim_count'];

                                            $check_wallet = mysqli_query($conn, "select * from in_wallet where deal_id='$deal_id' and user_id='$user_id'");
                                            if (mysqli_num_rows($check_wallet) > 0) {
                                                $wallet_bar['wallet_check'] = 'true';
                                            } else {
                                                $wallet_bar['wallet_check'] = 'false';
                                            }

                                            $check_claim = mysqli_query($conn, "select * from claim_deal where deal_id='$deal_id' and user_id='$user_id'");
                                            if (mysqli_num_rows($check_claim) > 0) {
                                                $wallet_bar['claimed_check'] = 'true';
                                            } else {
                                                $wallet_bar['claimed_check'] = 'false';
                                            }

                                            $check_impressions = mysqli_query($conn, "select * from impressions_deal where deal_id='$deal_id' and user_id='$user_id'");
                                            if (mysqli_num_rows($check_impressions) > 0) {
                                                $wallet_bar['impressions_check'] = 'true';
                                            } else {
                                                $wallet_bar['impressions_check'] = 'false';
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
        $response['message'] = 'Successfully Insert.';
    } else {
        // failed to insert row
        $response['success'] = 'false';
        $response['message'] = 'Oops! An error occurred. Error description: '.mysqli_error($conn);
        $response['user_id'] = '';
        $response['username'] = '';
        $response['user_name'] = '';
        $response['gender'] = '';
        $response['birthday'] = '';
        $response['email'] = '';
        $response['user_type'] = '';

        $response['bars'] = array();
        $response['wallets'] = array();
    }
} else {
    $response['success'] = 'false';
    $response['message'] = 'Failed';
    $response['user_id'] = '';
    $response['username'] = '';
    $response['user_name'] = '';
    $response['gender'] = '';
    $response['birthday'] = '';
    $response['email'] = '';
    $response['user_type'] = '';

    $response['bars'] = array();
    $response['wallets'] = array();
}

// echoing JSON response
echo json_encode($response);
//Close connection
mysqli_close($conn);
