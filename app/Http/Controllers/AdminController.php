<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

use App\Coins;

class AdminController extends Controller
{
    public function csvupload(){
        return view('admin.bar.csv_upload');
    }
    public function manual(){
        return view('admin.bar.manual_register');
    }
    public function scatter_coin(){
        $coin_positions = Coins::get_coin_positions(null);
        // dd($coin_positions);
        return view('admin.coins.scatter')->with('coin_positions', $coin_positions);
    }
    public function scatter_coin_post(){
        $longitude = Input::get('longitude');
        $latitude = Input::get('latitude');
        $coin_ct = Input::get('coinct');

        $group_entry = array(
            'group_latitude' => $latitude,
            'group_longitude' => $longitude,
            'group_coins' => $coin_ct
        );
        $groupid = Coins::insert_entry('coin_groups', $group_entry);

        $radius = 5;
        $r_earth = 6378.0;
        for($i = 0; $i < $coin_ct; $i++){
            $rand_dist = random_int(0, $radius * 1000) / 1000.0;
            $rand_angle = random_int(0, 2 * pi() * 10000) / 10000.0;
            $dx = $rand_dist * cos($rand_angle);
            $dy = $rand_dist * sin($rand_angle);
            
            $new_latitude  = $latitude  + ($dy / $r_earth) * (180 / pi());
            $new_longitude = $longitude + ($dx / $r_earth) * (180 / pi()) / cos($latitude * pi() / 180);

            $entry = array(
                'latitude' => $new_latitude,
                'longitude' => $new_longitude,
                'group' => $groupid
            );
            Coins::insert_entry('coin_pos', $entry);
        }
        return Redirect::to('admin/coin/scatter');
    }

    public function get_near_coins(){
        $latitude = Input::get('lat');
        $longitude = Input::get('lon');
        $range = 100;
        $coins = Coins::get_near_coins($latitude, $longitude, $range);
        return json_encode($coins);
    }
}
