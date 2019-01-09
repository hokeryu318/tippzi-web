<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class LoginController extends Controller
{
    public function index()
    {
        return view('login');

    }

    public function login(Request $request)
    {

        $login_res = $request->all();

        $client = new Client(); //GuzzleHttp\Client

        $res = $client->request('POST', url('/Tippzi/api/authorize/sign_in.php'), [
            'form_params' => [
                'username' => $login_res['email'],
                'password' => $login_res['password'],
                'lat' => '',
                'lon' => '',
            ]
        ]);

        $result = $res->getBody();

        $result = json_decode($result);

//        dd($result);exit;

//        dd($result->bars);exit;

//        dd($result->bars[0]->business_name);exit;

//        dd($result->bars[0]->open_time->mon_start);exit;

//        dd($result->bars[0]->gallery->background1);exit;

        return view('dashboard')->with(compact('result'));

    }


}

