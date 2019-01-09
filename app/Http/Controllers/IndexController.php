<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Components\PaypalComponent;

class IndexController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function donate()
    {
        return view('donate');
    }

    public function donate_post(Request $request)
    {
        // dd($request);
        $name = $request->name;
        $email = $request->email;
        $amount = $request->amount;
        $order_key = time() . "-" . uniqid(rand());

        $paypal = new PaypalComponent();
        $response = $paypal->setPayment($amount, $order_key);
        if($response){
            return redirect()->to($response['paypal_link']);
        }
    }

    public function paypal(Request $request)
    {
        $token = $request->get('token');
        $PayerID = $request->get('PayerID');
        $paypal = new PaypalComponent();
        $response = $paypal->getSuccessResponse($token, $PayerID);
        if($response){
            return view('donate')->with(compact('response'));
        }
    }
}
