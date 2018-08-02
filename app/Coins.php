<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class Coins extends Model
{
    public static function insert_entry($tbname, $entry){
        $check_insert = DB::table($tbname)->insert($entry);
        if ($check_insert) {
            return DB::getPdo()->lastInsertId();
        } else {
            return 0;
        }
    }

    public static function get_coin_positions($group){
        $sql = DB::table('coin_pos');
        if(isset($group)){
            $sql = $sql->where('group', $group);
        }
        return $sql->get();
    }

    public static function get_near_coins($lat, $lon, $range){
        $coin_positions = Coins::get_coin_positions(null);
        $result = array();
        foreach($coin_positions as $pos){
            $distance = Coins::distance($lat, $lon, $pos->latitude, $pos->longitude, 'K') * 1000;
            if($distance < $range){
                array_push($result, $pos);
            }
        }
        return $result;
    }

    public static function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
      
        if ($unit == "K") {
          return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
}
