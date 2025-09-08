<?php

namespace App\Models;

use App\CPU\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Phonepe extends Model
{
    const CREATED_AT = 'add_date_time';
    const UPDATED_AT = 'update_date_time';
    protected $table = 'about_logo';
    // protected $casts = [
    //     'parent_id' => 'integer',
    //     'position' => 'integer',
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime',
    //     'home_status' => 'integer',
    //     'priority' => 'integer'
    // ];


    public static function create_url($data)
    {        
        $order_amount = $data['amount'];
        $transaction_id = $data['transaction_id'];
        $redirectUrl = $data['redirectUrl'];
        
        // $order_amount = 1;

            
        $currency = 'INR';
        $data = array (
              'merchantId' => phone_pay_merchant_id,
              'merchantTransactionId' => $transaction_id,
            //   'order_id' => $transaction_id,
              'merchantUserId' => "MUID123",
              'amount' => $order_amount*100,
              'redirectUrl' => $redirectUrl,
              'redirectMode' => 'POST',
              'callbackUrl' => $redirectUrl,
              'mobileNumber' => '9958574223',
              'paymentInstrument' => 
              array (
                'type' => 'PAY_PAGE',
              ),
        );
        $encode = base64_encode(json_encode($data));
        $saltKey = phone_pay_secret_key;
        $saltIndex = 1;
        $string = $encode.'/pg/v1/pay'.$saltKey;
        $sha256 = hash('sha256',$string);
        $finalXHeader = $sha256.'###'.$saltIndex;
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.phonepe.com/apis/hermes/pg/v1/pay',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode(['request' => $encode]),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'X-VERIFY: '.$finalXHeader
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);
        
        // print_r($response);
        // die;
        $url = '';
        if($response->success==true)
        {
            $url = $response->data->instrumentResponse->redirectInfo->url;
        }
        else
        {
            $url = '';
        }
        return $url;        
    }
    
    public static function payment_status($transaction_id)
    {        
        $saltKey = phone_pay_secret_key;
        $saltIndex = 1;
        $string = "/pg/v1/status/".phone_pay_merchant_id."/".$transaction_id.$saltKey;
        $sha256 = hash('sha256',$string);
        $finalXHeader = $sha256.'###'.$saltIndex;    
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.phonepe.com/apis/hermes/pg/v1/status/'.phone_pay_merchant_id.'/'.$transaction_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'X-VERIFY: '.$finalXHeader,
            'X-MERCHANT-ID: '.phone_pay_merchant_id
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response = json_decode($response);              
    }
    
}
