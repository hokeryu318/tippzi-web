<?php

namespace App\Components;

use Srmklive\PayPal\Services\ExpressCheckout;

class PaypalComponent
{
    protected $provider;

    public function __construct()
    {
        try {
            $this->provider = new ExpressCheckout();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function setPayment($amount, $key)
    {
        // dd($this->provider);
        session(['amount' => $amount, 'order_key' => $key]);
        $data = $this->getCheckoutData($amount, $key);
        try{
            $response = $this->provider->setCurrency('USD')->setExpressCheckout($data);
            return $response;
        } catch(\Exception $e){
            return false;
        }
    }

    public function getCheckoutData($amount, $key)
    {
        $data = [
            'items' => [
                [
                    'name' => 'Donate to Tippzi',
                    'price' => $amount,
                    'qty' => 1
                ]
            ]
        ];
        $data['invoice_id'] = 'Tippzi_Donate_'.$key;
        $data['invoice_description'] = "Tippzi_Donate_ #{$data['invoice_id']} Invoice";
        $data['return_url'] = route('donate.paypal');
        $data['cancel_url'] = route('donate');
        $data['total'] = $amount;
        return $data;
    }

    public function getSuccessResponse($token, $PayerID)
    {
        $response = $this->provider->getExpressCheckoutDetails($token);
        $amount = session('amount');
        $order_key = session('order_key');
        $data = $this->getCheckoutData($amount, $order_key);
        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            // Perform transaction on PayPal
            $payment_status = $this->provider
                                ->setCurrency($response['PAYMENTREQUEST_0_CURRENCYCODE'])
                                ->doExpressCheckoutPayment($data, $token, $PayerID);
            $status = $payment_status['PAYMENTINFO_0_PAYMENTSTATUS'];
            if (!strcasecmp($status, 'Completed') || !strcasecmp($status, 'Processed')) {
                return true;
            } else {
                return false;
            }
        }
    }
}
