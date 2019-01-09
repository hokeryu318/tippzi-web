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
    $bar_id = $json_data['bar_id'];
    $title = conv_str($json_data['title']);
    $description = conv_str($json_data['description']);
    $duration = $json_data['duration'];
    $qty = $json_data['qty'];
    $deal_days = $json_data['deal_days'];
} elseif (isset($_REQUEST['user_id'])) {
    $user_id = $_REQUEST['user_id'];
    $bar_id = $_REQUEST['bar_id'];
    $description = conv_str($_REQUEST['description']);
    $duration = $_REQUEST['duration'];
    $qty = $_REQUEST['qty'];
    $title = conv_str($_REQUEST['title']);
    $deal_days = $_REQUEST['deal_days'];
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

//	$deal_bar_insert  = mysqli_query($conn, "insert into deal(bar_id, title, description, duration, qty, impressions, in_wallet, claimed)
//											value('$bar_id','$title','$description','$duration','$qty','','','')");

$deal_bar_insert = mysqli_query($conn, "insert into deal(bar_id, title, description, duration, qty)
											value('$bar_id','$title','$description','$duration','$qty')");

    if ($deal_bar_insert) {
        $deal_id = $conn->insert_id;
        $index = 0;
        if ($deal_days['monday'] == 'True') {
            $monday_flag = 1;
        } else {
            $monday_flag = 0;
        }
        if ($deal_days['tuesday'] == 'True') {
            $tuesday_flag = 1;
        } else {
            $tuesday_flag = 0;
        }
        if ($deal_days['wednesday'] == 'True') {
            $wednesday_flag = 1;
        } else {
            $wednesday_flag = 0;
        }
        if ($deal_days['thursday'] == 'True') {
            $thursday_flag = 1;
        } else {
            $thursday_flag = 0;
        }
        if ($deal_days['friday'] == 'True') {
            $friday_flag = 1;
        } else {
            $friday_flag = 0;
        }
        if ($deal_days['saturday'] == 'True') {
            $saturday_flag = 1;
        } else {
            $saturday_flag = 0;
        }
        if ($deal_days['sunday'] == 'True') {
            $sunday_flag = 1;
        } else {
            $sunday_flag = 0;
        }

        $deal_days_insert = mysqli_query($conn, "insert into deal_days (`deal_id`,`monday`,`tuesday`,`wednesday`,`thursday`,`friday`,`saturday`,`sunday`) 
		value('$deal_id', '$monday_flag','$tuesday_flag','$wednesday_flag','$thursday_flag','$friday_flag','$saturday_flag','$sunday_flag')");

        $select_business = mysqli_query($conn, "select * from business_user where Id='$user_id'");
        $user_row = mysqli_fetch_array($select_business);
        $response['user_id'] = $user_row['Id'];
        $response['username'] = $user_row['username'];
        $response['email'] = $user_row['email'];
        $response['telephone'] = $user_row['telephone'];
        $response['user_type'] = '2';

        $user_id = $user_row['Id'];
        $response['bars'] = array();
        $check_bars = mysqli_query($conn, "select * from bar where user_id='$user_id'");
        while ($bar_row = mysqli_fetch_array($check_bars)) {
            $bar_list = array();
            $sel_bar_id = $bar_row['Id'];
            $bar_list['bar_id'] = $bar_row['Id'];
            $bar_list['business_name'] = conv_str($bar_row['name']);
            $bar_list['post_code'] = $bar_row['post_code'];
            $bar_list['address'] = $bar_row['address'];
            $bar_list['service_name'] = conv_str($bar_row['service_name']);
            $bar_list['telephone'] = $bar_row['telephone'];
            $bar_list['website'] = $bar_row['website'];
            $bar_list['email'] = $bar_row['email'];
            $bar_list['description'] = conv_str($bar_row['description']);
            $bar_list['music_type'] = $bar_row['music_type'];
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
                $count_row = mysqli_fetch_array($count_res);
                $dealbarsarray['in_wallet'] = $count_row['count_wallet'];

                $impresscount_res = mysqli_query($conn, "select count(Id) as impress_count from impressions_deal where deal_id='$sel_bar_id'");
                $impresscount_row = mysqli_fetch_array($impresscount_res);
                $dealbarsarray['impressions'] = $impresscount_row['impress_count'];

                $check_bar = mysqli_query($conn, "select * from engagement where bar_id='$sel_bar_id'");
                if (mysqli_num_rows($check_bar) > 0) {
                    $engagement_res = mysqli_query($conn, "select count(Id) as engagement_count from engagement where bar_id='$sel_bar_id'");
                    $engagement_row = mysqli_fetch_array($engagement_res);
                    $dealbarsarray['engagement'] = $engagement_row['engagement_count'];
                } else {
                    $dealbarsarray['engagement'] = '0';
                }

                $check_bar1 = mysqli_query($conn, "select * from clicks where bar_id='$sel_bar_id'");
                if (mysqli_num_rows($check_bar1) > 0) {
                    $clicks_res = mysqli_query($conn, "select count(Id) as engagement_count from clicks where bar_id='$sel_bar_id'");
                    $clicks_row = mysqli_fetch_array($clicks_res);
                    $dealbarsarray['clicks'] = $clicks_row['engagement_count'];
                } else {
                    $dealbarsarray['clicks'] = '0';
                }

                $claimcount_res = mysqli_query($conn, "select count(Id) as claim_count from claim_deal where deal_id='$deal_id'");
                $claimcount_row = mysqli_fetch_array($claimcount_res);
                $dealbarsarray['claimed'] = $claimcount_row['claim_count'];
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

        $response['success'] = 'true';
        $response['message'] = 'Your Businessinfo is successfully created in here.';
    } else {
        // failed to insert row
        $response['success'] = 'false';
        $response['message'] = 'Oops! An error occurred. Error description: '.mysqli_error($conn);

        $response['user_id'] = '';
        $response['username'] = '';
        $response['email'] = '';
        $response['telephone'] = '';
        $response['user_type'] = '';
        $response['bars'] = array();
    }

// echoing JSON response
echo json_encode($response);
//Close connection
mysqli_close($conn);
