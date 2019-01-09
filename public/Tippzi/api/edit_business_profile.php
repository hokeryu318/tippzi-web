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
    $bar_id = $json_data['bar_id'];
    $address = conv_str($json_data['address']);
    $business_name = conv_str($json_data['business_name']);
    $category = conv_str($json_data['category']);
    $description = conv_str($json_data['description']);
    $email = $json_data['email'];
    $lat = $json_data['lat'];
    $lon = $json_data['lon'];
    $music_type = conv_str($json_data['music_type']);
    $post_code = $json_data['post_code'];
    $telephone = $json_data['telephone'];
    $website = $json_data['website'];
    $galleryModel = $json_data['galleryModel'];
    $open_time = $json_data['open_time'];
} elseif (isset($_REQUEST['bar_id'])) {
    $bar_id = $_REQUEST['bar_id'];
    $address = conv_str($_REQUEST['address']);
    $business_name = conv_str($_REQUEST['business_name']);
    $category = conv_str($_REQUEST['category']);
    $description = conv_str($_REQUEST['description']);
    $email = $_REQUEST['email'];
    $lat = $_REQUEST['lat'];
    $lon = $_REQUEST['lon'];
    $music_type = conv_str($_REQUEST['music_type']);
    $post_code = $_REQUEST['post_code'];
    $telephone = $_REQUEST['telephone'];
    $website = $_REQUEST['website'];
    $galleryModel = $_REQUEST['galleryModel'];
    $open_time = $_REQUEST['open_time'];
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

$update_bar_res = mysqli_query($conn, "update bar set name='$business_name', post_code='$post_code',
													  address='$address',
													  telephone='$telephone',
													  website='$website',
													  email='$email',
													  description='$description',
													  music_type='$music_type',
													  latitude='$lat',
													  longitude='$lon',
													  category='$category' where Id='$bar_id'");
if ($update_bar_res) {
    $background_image1 = $galleryModel['background1'];
    $background_image2 = $galleryModel['background2'];
    $background_image3 = $galleryModel['background3'];
    $background_image4 = $galleryModel['background4'];
    $background_image5 = $galleryModel['background5'];
    $background_image6 = $galleryModel['background6'];

    if ($background_image1 != '') {
        if ($background_image1 == 'close') {
            $image_name1 = 'close';
        } else {
            $get_name1 = rand(1000, 1000000);
            $image_name1 = $get_name1.'.jpg';
            $image_path1 = '../../upload/'.$image_name1;
            file_put_contents($image_path1, base64_decode($background_image1));
        }
    } else {
        $image_name1 = '';
    }
    if ($background_image2 != '') {
        if ($background_image2 == 'close') {
            $image_name2 = 'close';
        } else {
            $get_name2 = rand(1000, 1000000);
            $image_name2 = $get_name2.'.jpg';
            $image_path2 = '../../upload/'.$image_name2;
            file_put_contents($image_path2, base64_decode($background_image2));
        }
    } else {
        $image_name2 = '';
    }
    if ($background_image3 != '') {
        if ($background_image3 == 'close') {
            $image_name3 = 'close';
        } else {
            $get_name3 = rand(1000, 1000000);
            $image_name3 = $get_name3.'.jpg';
            $image_path3 = '../../upload/'.$image_name3;
            file_put_contents($image_path3, base64_decode($background_image3));
        }
    } else {
        $image_name3 = '';
    }
    if ($background_image4 != '') {
        if ($background_image4 == 'close') {
            $image_name4 = 'close';
        } else {
            $get_name4 = rand(1000, 1000000);
            $image_name4 = $get_name4.'.jpg';
            $image_path4 = '../../upload/'.$image_name4;
            file_put_contents($image_path4, base64_decode($background_image4));
        }
    } else {
        $image_name4 = '';
    }
    if ($background_image5 != '') {
        if ($background_image5 == 'close') {
            $image_name5 = 'close';
        } else {
            $get_name5 = rand(1000, 1000000);
            $image_name5 = $get_name5.'.jpg';
            $image_path5 = '../../upload/'.$image_name5;
            file_put_contents($image_path5, base64_decode($background_image5));
        }
    } else {
        $image_name5 = '';
    }

    if ($background_image6 != '') {
        if ($background_image6 == 'close') {
            $image_name6 = 'close';
        } else {
            $get_name6 = rand(1000, 1000000);
            $image_name6 = $get_name6.'.jpg';
            $image_path6 = '../../upload/'.$image_name6;
            file_put_contents($image_path6, base64_decode($background_image6));
        }
    } else {
        $image_name6 = '';
    }

    $check_bar = mysqli_query($conn, "select * from bar_gallery where bar_id='$bar_id'");
    if (mysqli_num_rows($check_bar) > 0) {
        $get_gallery_row = mysqli_fetch_array($check_bar);
        if ($image_name1 == 'close') {
            $image_name1 = '';
        } elseif ($image_name1 == '') {
            $image_name1 = $get_gallery_row['background_1'];
        }
        if ($image_name2 == 'close') {
            $image_name2 = '';
        } elseif ($image_name2 == '') {
            $image_name2 = $get_gallery_row['background_2'];
        }
        if ($image_name3 == 'close') {
            $image_name3 = '';
        } elseif ($image_name3 == '') {
            $image_name3 = $get_gallery_row['background_3'];
        }
        if ($image_name4 == 'close') {
            $image_name4 = '';
        } elseif ($image_name4 == '') {
            $image_name4 = $get_gallery_row['background_4'];
        }
        if ($image_name5 == 'close') {
            $image_name5 = '';
        } elseif ($image_name5 == '') {
            $image_name5 = $get_gallery_row['background_5'];
        }
        if ($image_name6 == 'close') {
            $image_name6 = '';
        } elseif ($image_name6 == '') {
            $image_name6 = $get_gallery_row['background_6'];
        }

        $update_res = mysqli_query($conn, "update bar_gallery set background_1 ='$image_name1',
																  background_2 ='$image_name2',
																  background_3 ='$image_name3',
																  background_4 ='$image_name4',
																  background_5 ='$image_name5',
																  background_6 ='$image_name6' where bar_id='$bar_id'");
    } else {
        $insert_res = mysqli_query($conn, "insert into bar_gallery(`bar_id`, `background_1`,`background_2`,`background_3`,`background_4`,`background_5`,`background_6`) value ('$bar_id','$image_name1','$image_name2','$image_name3','$image_name4','$image_name5','$image_name6')");
    }

    $check_opentime = mysqli_query($conn, "select * from bar_open_time where bar_id = '$bar_id'");
    $mon_start = $open_time['mon_start'];
    $mon_end = $open_time['mon_end'];
    $tue_start = $open_time['tue_start'];
    $tue_end = $open_time['tue_end'];
    $wed_start = $open_time['wed_start'];
    $wed_end = $open_time['wed_end'];
    $thur_start = $open_time['thur_start'];
    $thur_end = $open_time['thur_end'];
    $fri_start = $open_time['fri_start'];
    $fri_end = $open_time['fri_end'];
    $sat_start = $open_time['sat_start'];
    $sat_end = $open_time['sat_end'];
    $sun_start = $open_time['sun_start'];
    $sun_end = $open_time['sun_end'];

    if (mysqli_num_rows($check_opentime) > 0) {
        $update_res = mysqli_query($conn, "update bar_open_time set mon_start='$mon_start', 
																	mon_end='$mon_end' ,
																	tue_start='$tue_start', 
																	tue_end='$tue_end', 
																	wed_start='$wed_start', 
																	wed_end='$wed_end', 
																	thur_start='$thur_start', 
																	thur_end='$thur_end',
																	fri_start='$fri_start', 
																	fri_end='$fri_end', 
																	sat_start='$sat_start', 
																	sat_end='$sat_end', 
																	sun_start='$sun_start',
																	sun_end='$sun_end' where bar_id='$bar_id'");
    } else {
        $insert_res = mysqli_query($conn, "insert into bar_open_time(`bar_id`,`mon_start`,`mon_end` 
																				 ,`tue_start`,`tue_end`,
																				 `wed_start`,`wed_end`,
																				 `thur_start`,`thur_end`,
																				 `fri_start`,`fri_end`,
																				 `sat_start`,`sat_end`,
																				 `sun_start`,`sun_end`) 
																 value ('$bar_id','$mon_start','$mon_end','$tue_start','$tue_end','$wed_start','$wed_end','$thur_start','$thur_end','$fri_start','$fri_end','$sat_start','$sat_end','$sun_start','$sun_end')");
    }

    $get_user_id = mysqli_query($conn, "select * from bar where Id='$bar_id'");
    $bar_row = mysqli_fetch_array($get_user_id);
    $user_id = $bar_row['user_id'];
    $response['success'] = 'true';
    $response['message'] = 'Your account is successfully created in here.';
    $check_user = mysqli_query($conn, "select * from business_user where Id='$user_id'");
    $user_row = mysqli_fetch_array($check_user);
    $response['user_id'] = $user_id;
    $response['username'] = $user_row['username'];
    $response['email'] = $user_row['email'];
    $response['telephone'] = $user_row['telephone'];
    $response['user_type'] = '2';

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

            $claimcount_res = mysqli_query($conn, "select count(Id) as claim_count from claim_deal where deal_id='$deal_id'");
            $claimcount_row = mysqli_fetch_array($claimcount_res);
            $dealbarsarray['claimed'] = $claimcount_row['claim_count'];

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
        //		$response["sssss"] = "select count(Id) as count_wallet from claim_deal where deal_id='$deal_id'";
    }
} else {
    $response['success'] = 'false';
    $response['message'] = 'Insert failed';

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
