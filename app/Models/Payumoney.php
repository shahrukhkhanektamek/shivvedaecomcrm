<?php

namespace App\Models;

use App\CPU\Helpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Payumoney extends Model
{
    const CREATED_AT = 'add_date_time';
    const UPDATED_AT = 'update_date_time';
    protected $table = 'about_logo';










    public static function create_checksum($data)
    {
        $orders = $data['orders'];
        $user = $data['user'];
        $MERCHANT_KEY = PAYU_MERCHANT_KEY;
        $SALT = PAYU_SALT;
        $txnid = $data['transaction_id'];
        $PAYU_BASE_URL = PAYU_BASE_URL;


        $amount = $data['amount'];
        $productinfo = $orders->product_name;
        $firstname = explode(" ",$user->name)[0];
        $email = $user->email;
        $phone = $user->phone;
        $success_url = $data['redirectUrl'];
        $failed_url = $data['redirectFUrl'];
        $cancelled_url = $data['redirectCUrl'];


        $Lastname = '';
        $Zipcode = '';
        $city = '';
        $state = '';
        $country = '';
        $udf1 = '';
        $udf2 = '';
        $udf5 = '';


        $hash=hash('sha512', $MERCHANT_KEY.'|'.$txnid.'|'.$amount.'|'.$productinfo.'|'.$firstname.'|'.$email.'|'.$udf1.'|'.$udf2.'|||'.$udf5.'||||||'.$SALT);


        $html = '<form action="'.$PAYU_BASE_URL.'/_payment" id="payment_form_submit" method="post">
          <input type="hidden" id="udf5" name="udf5" value="'.$udf5.'" />
           <input type="hidden" id="udf1" name="udf1" value="'.$udf1.'" />
           <input type="hidden" id="udf1" name="udf2" value="'.$udf2.'" />
          <input type="hidden" id="surl" name="surl" value="'.$success_url.'" />
          <input type="hidden" id="furl" name="furl" value="'.$failed_url.'" />
          <input type="hidden" id="curl" name="curl" value="'.$cancelled_url.'" />
          <input type="hidden" id="key" name="key" value="'.$MERCHANT_KEY.'" />
          <input type="hidden" id="txnid" name="txnid" value="'.$txnid.'" />
          <input type="hidden" id="amount" name="amount" value="'.$amount.'" />

          <input type="hidden" id="productinfo" name="productinfo" value="'.$productinfo.'" />
          <input type="hidden" id="firstname" name="firstname" value="'.$firstname.'" />
          <input type="hidden" id="Lastname" name="Lastname" value="'.$Lastname.'" />
          <input type="hidden" id="Zipcode" name="Zipcode" value="'.$Zipcode.'" />
          <input type="hidden" id="email" name="email" value="'.$email.'" />
          <input type="hidden" id="phone" name="phone" value="'.$phone.'" />
          <input type="hidden" id="address1" name="address1" value="" />
          <input type="hidden" id="address2" name="address2" value="" />
          <input type="hidden" id="city" name="city" value="'.$city.'" />
          <input type="hidden" id="state" name="state" value="'.$state.'" />
          <input type="hidden" id="country" name="country" value="'.$country.'" />
          <input type="hidden" id="Pg" name="Pg" value="Pay" />
          <input type="hidden" id="hash" name="hash" value="'.$hash.'" />
          </form>
          <script type="text/javascript">
            document.getElementById("payment_form_submit").submit();
          </script>';


        return $html;
    }


    
    public static function payment_status($transaction_id)
    {
        $hash = hash('sha512',PAYU_MERCHANT_KEY.'|verify_payment'.'|'.$transaction_id.'|'.PAYU_SALT);
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://info.payu.in/merchant/postservice?form=2',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('key' => PAYU_MERCHANT_KEY,'command' => 'verify_payment','hash' => $hash,'var1' => $transaction_id),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response = json_decode((($response)));
    }
    
}
