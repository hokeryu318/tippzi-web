<?php

namespace App;
use DB;
use Hash;
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
        return $sql->where('status', '1')->get();
    }

    public static function get_near_coins($lat, $lon, $range){
        $coin_positions = Coins::get_coin_positions(null);
        $result = array();
        foreach($coin_positions as $pos){
            $distance = Coins::distance($lat, $lon, $pos->latitude, $pos->longitude, 'K') * 1000;
            if($distance < $range && $pos->status == '1'){
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

    public static function get_coin($customerid, $coinid, $lat, $lon, $token){
        if(!DB::table('customer_user')->where('id', $customerid)->exists()){
            return array(
                'result' => 'failure',
                'message' => 'Customer id not found'
            );
        }
        if(!DB::table('coin_pos')->where('id', $coinid)->exists()){
            return array(
                'result' => 'failure',
                'message' => 'Coin id not found'
            );
        }
        if(DB::table('coin_pos')->where('id', $coinid)->get()->first()->status == 0){
            return array(
                'result' => 'failure',
                'message' => 'Coin is already taken'
            );
        }
        $coin = DB::table('coin_pos')->where('id', $coinid)->get()->first();
        $distance = Coins::distance($lat, $lon, $coin->latitude, $coin->longitude, 'K') * 1000;
        // dd($distance);
        if($distance > 5){
            return array(
                'result' => 'failure',
                'message' => 'Not in range of coin'
            );
        }
        // dd(Hash::check($coin->latitude.$coin->longitude, '$2y$10$Cl8BAyTBv2C.fWn0u.e4v.q.ycGCE1pE6xIEl/RyJCKh6vmVN6MYm'));
        // if(!Hash::check($coin->latitude.$coin->longitude, $token)){
        //     return array(
        //         'result' => 'failure',
        //         'message' => 'Invalid token'
        //     );
        // }


        $customer = DB::table('coin_customers')->where('customer_id', $customerid)->get()->first();
        if(isset($customer)){
            $entry = array(
                'coin_count' => $customer->coin_count + 1
            );
            DB::table('coin_customers')->where('customer_id', $customerid)->update($entry);
        } else {
            $entry = array(
                'customer_id' => $customerid,
                'coin_count' => 1
            );
            Coins::insert_entry('coin_customers', $entry);
        }
        $customer = DB::table('coin_customers')->where('customer_id', $customerid)->get()->first();
        DB::table('coin_pos')->where('id', $coinid)->update(['status' => 0]);
        return array(
            'result' => 'success',
            'coins' => $customer->coin_count
        );
    }

    public static function get_coin_amt($customerid){
        $customer = DB::table('coin_customers')->where('customer_id', $customerid)->get()->first();
        if(isset($customer)){
            return $customer->coin_count;
        } else {
            return 0;
        }
    }

    public static function reset_customer_coin($customerid){
        DB::table('coin_customers')->where('customer_id', $customerid)->update(['coin_count' => 0]);
    }
}
