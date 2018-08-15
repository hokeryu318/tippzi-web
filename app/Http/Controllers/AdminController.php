<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

use App\Coins;
use Web3\Web3;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Hash;

use Ethereum\Ethereum;

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

        $radius = 1.1515;
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
                'group' => $groupid,
                'token' => Hash::make($new_latitude.$new_longitude)
            );
            Coins::insert_entry('coin_pos', $entry);
        }
        return Redirect::to('admin/coin/scatter');
    }

    public function get_near_coins(Request $request){
        $latitude = $request->input('lat');
        $longitude = $request->input('lon');
        $customerid = $request->input('customer');
        $range = 1609;
        $coins = Coins::get_near_coins($latitude, $longitude, $range);
        return json_encode($coins);
    }

    public function withdraw_coin(Request $request){
        $customerid = $request->input('customer');
        $toAccount = $request->input('walletid');
        $fromAccount = '0xaa9a46f9e81749e9fee0af36ba249f22ed77dbe1';
        // $toAccount = '0xff75eb627fae7f2aa7ab3857e8316b6dd8d4afa7';
        $amt = Coins::get_coin_amt($customerid);
        
        // dd($amt);
        if($amt < 0){
            return "Invalid withdraw";
        }
        $web3 = new Web3('http://localhost:8545');
        $eth = $web3->eth;

        $eth->accounts(function ($err, $accounts) use ($eth, $fromAccount, $toAccount, $amt, $customerid) {
            if ($err !== null) {
                echo json_encode(['result' => 'failure', 'message' => $err->getMessage()]);
                return;
            }
        
            // send transaction
            $eth->sendTransaction([
                'from' => $fromAccount,
                'to' => $toAccount,
                'value' => (int)$amt
            ], function ($err, $transaction) use ($eth, $fromAccount, $toAccount, $customerid) {
                if ($err !== null) {
                    echo json_encode(['result' => 'failure', 'message' => $err->getMessage()]);
                    return;
                }
                echo json_encode(['result' => 'success', 'txhash' => $transaction]);
                Coins::reset_customer_coin($customerid);
            });
        });
    }

    public function get_coin(Request $request){
        $customer = $request->input('customer');
        $coin = $request->input('coin');
        $lat = $request->input('lat');
        $lon = $request->input('lon');
        $token = $request->input('token');
        return Coins::get_coin($customer, $coin, $lat, $lon, $token);
    }

    public function get_coin_count(Request $request){
        $customer = $request->input('customer');
        return array("coinct" => Coins::get_coin_amt($customer));
    }

    public function get_coin_available(Request $request){
        $coin = $request->input('coin');
        $result = Coins::get_coin_available($coin);
        echo json_encode(['result' => $result]);
    }
}
