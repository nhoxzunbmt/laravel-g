<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Omnipay;

class PaymentController extends Controller
{
    public function purchase($driver, Request $request)
    {
        Omnipay::setGateway($driver);
        
        //create order_id
        $order_id = 1;
        
        $params = [
          'cancelUrl' => url('/api/payment/'.$driver.'/cancel?cid=' . $order_id),
          'returnUrl' => url('/api/payment/'.$driver.'/success?cid=' . $order_id."&driver=".$driver),
          'amount' => $request->input('amount') . ".00",
          'currency' => "USD",
        ];
        
        $items = [
            [
                'name' => 'Crediting of funds to the account',
                'quantity' => 1,
                'price' => $request->input('amount') . ".00"
            ]
        ];
        
        //dd($params);
        
        $response = Omnipay::purchase($params)->setItems($items)->send();
        $transactionReference = $response->getTransactionReference();
        $params['transactionReference'] = $transactionReference;
        
        \Session::put('params', $params);
        \Session::save();

        if ($response->isRedirect()) {
             // redirect to offsite payment gateway
            $response->redirect();
        } else {
            dd($response->getMessage());
        }
    }
    
    public function handleCancel($driver, Request $request)
    {
        echo "Something went wrong please try again.";
    }
    
    public function handleSuccess($driver, Request $request)
    {
        Omnipay::setGateway($driver);
        
        $params = \Session::get('params');
        $payerId = $request->input('PayerID');
        $token = $request->input('token');
        $paymentId = $request->input('paymentId');
        $params['payerId'] = $payerId;
        
        $response = Omnipay::completePurchase($params)->send();
        if ($response->isSuccessful()) 
        {
            dd($response->getData());   
        }else{
            print_r($response);
            exit;
        }
    }
}