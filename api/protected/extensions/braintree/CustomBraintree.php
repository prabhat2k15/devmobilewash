<?php

/**
 * CustomBraintree.php
 *
 * @author: Rohan M <rohan@thetatechnolabs.com>
 * Date: 26/11/2015
 * By phpstrom
 */
class CustomBraintree extends CApplicationComponent
{
    public $ENV;// "sandbox" or "live"


    public $MERCHANT_ID;

    public $MERCHANT_ACCOUNT_ID;

    public $PUBLIC_KEY;

    public $PRIVATE_KEY;

    public $CSEK;/* Client side encription key */


    public function init()
    {

        require_once 'braintree-php/lib/Braintree.php';

        Braintree_Configuration::environment($this->ENV);
        Braintree_Configuration::merchantId($this->MERCHANT_ID);
        Braintree_Configuration::publicKey($this->PUBLIC_KEY);
        Braintree_Configuration::privateKey($this->PRIVATE_KEY);

    }

    public function __construct()
    {

    }


    public function sale($data)
    {
        $result = Braintree_Transaction::sale($data);

        if ($result->success) {

            return (array('success' => 1,
                'transaction_id' => $result->transaction->id
            ));


        } else if ($result->transaction) {
            //Error processing transaction
            return (array(
                'success' => 0,
                'message' => $result->message,
                'code' => $result->transaction->processorResponseCode,
                'text' => $result->transaction->processorResponseText
            ));


        } else {
            //Validation Errors


            return (array(
                'success' => 0,
                'message' => $result->message,
                'validation_errors' =>(array)$result->errors->deepAll(),

            ));

        }


    }


 public function sale_real($data)
    {

Braintree_Configuration::environment('production');
        Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
        Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
        Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');

        $result = Braintree_Transaction::sale($data);

        if ($result->success) {

            return (array('success' => 1,
                'transaction_id' => $result->transaction->id
            ));


        } else if ($result->transaction) {
            //Error processing transaction
            return (array(
                'success' => 0,
                'message' => $result->message,
                'code' => $result->transaction->processorResponseCode,
                'text' => $result->transaction->processorResponseText
            ));


        } else {
            //Validation Errors


            return (array(
                'success' => 0,
                'message' => $result->message,
                'validation_errors' =>(array)$result->errors->deepAll(),

            ));

        }


    }

    public function createCustomer($data)
    {
        $result = Braintree_Customer::create($data);

        if ($result->success) {
        	 $clientToken = Braintree_ClientToken::generate((array("customerId" => $result->customer->id)));
            return (array(
                            'success' => 1,
                            'customer_id' => $result->customer->id,
                            'token' => $clientToken
                        ));
        } else {

            $errors = $result->errors->deepAll();
            if (count($errors) > 0 && ($errors[0]->code == 91609 || $errors[0]->message == 'Customer ID has already been taken.')) {
                return (array(
                    'success' => 1,
                    'customer_id' => $data['id'],

                ));
            }

            return (array(
                'success' => 0,
                'validation_errors' => $result->errors->deepAll(),

            ));

        }
    }

 public function createCustomer_real($data)
    {


Braintree_Configuration::environment('production');
        Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
        Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
        Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');

        $result = Braintree_Customer::create($data);

        if ($result->success) {
        	 $clientToken = Braintree_ClientToken::generate((array("customerId" => $result->customer->id)));
            return (array(
                            'success' => 1,
                            'customer_id' => $result->customer->id,
                            'token' => $clientToken
                        ));
        } else {

            $errors = $result->errors->deepAll();
            if (count($errors) > 0 && ($errors[0]->code == 91609 || $errors[0]->message == 'Customer ID has already been taken.')) {
                return (array(
                    'success' => 1,
                    'customer_id' => $data['id'],

                ));
            }

            return (array(
                'success' => 0,
                'validation_errors' => $result->errors->deepAll(),

            ));

        }
    }


  public function updateCustomer($id, $data)
    {

try {
            $result = Braintree_Customer::update($id, $data);

if ($result->success) {

            return (array(
                'success' => 1
            ));

        } else {
            return (array(
                'success' => 0,
                'errors' => $result->errors->deepAll(),

            ));

        }

        } catch (Exception $e) {
            return (array(
                'success' => 0,
                'message' => 'error in updating customer',

            ));
        }

    }


public function updateCustomer_real($id, $data)
    {

Braintree_Configuration::environment('production');
        Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
        Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
        Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');


try {
            $result = Braintree_Customer::update($id, $data);

if ($result->success) {

            return (array(
                'success' => 1
            ));

        } else {
            return (array(
                'success' => 0,
                'errors' => $result->errors->deepAll(),

            ));

        }

        } catch (Exception $e) {
            return (array(
                'success' => 0,
                'message' => 'error in updating customer',

            ));
        }

    }

    public function createClientToken($customer_id) {


try {
            $customer = Braintree_Customer::find($customer_id);
            if($customer){
                $clientToken = Braintree_ClientToken::generate((array("customerId" => $customer->id)));
                return (array("success"=>1,"customer_id"=>$customer->id,"token"=>$clientToken));
            }
        } catch (Exception $e) {
            return (array(
                'success' => 0,
                'message' => 'Customer with ' . $customer_id . " is not found",

            ));
        }
    }

 public function createClientToken_real($customer_id) {


Braintree_Configuration::environment('production');
        Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
        Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
        Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');

 try {
            $customer = Braintree_Customer::find($customer_id);
            if($customer){
                $clientToken = Braintree_ClientToken::generate((array("customerId" => $customer->id)));
                return (array("success"=>1,"customer_id"=>$customer->id,"token"=>$clientToken));
            }
        } catch (Exception $e) {
            return (array(
                'success' => 0,
                'message' => 'Customer with ' . $customer_id . " is not found",

            ));
        }
    }

    public function deleteCustomer($id)
    {
        $result = Braintree_Customer::delete($id);


        /*
        echo "<pre>";
        print_r($result);
        echo "</pre>";
        */

        if ($result->success) {

            return ($result);
            /*
          return(array('success'=>1,
                         'customer_id'=>$result->customer->id
                  ));
                  */

        } else {
            return (array(
                'success' => 0,
                'validation_errors' => $result->errors->deepAll(),

            ));

        }
    }


    public function getCustomerById($customer_id)
    {


        try {
            $customer = Braintree_Customer::find($customer_id);

            return ($customer);
        } catch (Exception $e) {
            return (array(
                'success' => 0,
                'message' => 'Customer with ' . $customer_id . " is not found",

            ));
        }


        /*
           eg:Array
            (
            [success] => 1
            [customer_id] => 68012283
            )
         */
        return ($customer);
    }


public function getCustomerById_real($customer_id)
    {

		Braintree_Configuration::environment('production');
        Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
        Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
        Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');

        try {
            $customer = Braintree_Customer::find($customer_id);

            return ($customer);
        } catch (Exception $e) {
            return (array(
                'success' => 0,
                'message' => 'Customer with ' . $customer_id . " is not found",

            ));
        }


        /*
           eg:Array
            (
            [success] => 1
            [customer_id] => 68012283
            )
         */
        return ($customer);
    }

    public function getPaymentMethodToken($customer_id)
    {
        try {
            $customer = Braintree_Customer::find($customer_id);

        } catch (Exception $e) {
            return (array(
                'success' => 0,
                'message' => 'Customer with ' . $customer_id . " is not found"
            ));
        }


        if (isset($customer->creditCards[0]->token)) {
            return (array(

                'success' => 1,
                'payment_method_token' => $customer->creditCards[0]->token
            ));
            /*
              Array
       (
           [success] => 1
           [payment_method_token] => 7nv6bm
       )
            */

        } else {
            return (array(
                'success' => 0,
                'message' => 'no creditCards found for the customer:' . $customer_id
            ));
        }
    }

  public function addPaymentMethod($data){
$all_errors = '';
$all_errors_mob = '';
$result = Braintree_PaymentMethod::create($data);

if(!$result->success){

foreach($result->errors->deepAll() AS $error) {
  $all_errors .= $error->message . "<br>";
  $all_errors_mob .= $error->message . "\r\n";
}

if(!$all_errors) $all_errors = $result->message;
if(!$all_errors_mob) $all_errors_mob = $result->message;

return (array(
                'success' => 0,
                'message' => $all_errors,
'message_mob' => $all_errors_mob
            ));

}

if($result->success){
$paytoken = $result->paymentMethod->token;

$card_ending_no = $result->paymentMethod->last4;
$card_type = $result->paymentMethod->cardType;
return (array(
                'success' => 1,
                'message' => 'payment method added',
'token' => $paytoken,
'card_ending_no' => $card_ending_no,
'card_type' => $card_type,
'masked_number' => $result->paymentMethod->maskedNumber
            ));
}

     }

public function extractUniqueId($creditCard){
    return $creditCard->uniqueNumberIdentifier;
}


/*
  public function addPaymentMethod_real($data){

Braintree_Configuration::environment('production');
        Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
        Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
        Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');

$all_errors = '';
$all_errors_mob = '';

$customer = Braintree_Customer::find($data['customerId']);
$unique_ids = array_map(extractUniqueId,$customer->creditCards);

$result = Braintree_PaymentMethod::create($data);

if ($result->success) {
    if(in_array(extractUniqueId($result->paymentMethod), $unique_ids)) {
       $result = Braintree_PaymentMethod::delete($result->paymentMethod->token);
return (array(
                 'success' => 0,
                'message' => 'Payment method already exists',
'message_mob' => 'Payment method already exists'
            ));
    }
else{
$paytoken = $result->paymentMethod->token;

$card_ending_no = $result->paymentMethod->last4;
$card_type = $result->paymentMethod->cardType;
return (array(
                'success' => 1,
                'message' => 'payment method added',
'token' => $paytoken,
'card_ending_no' => $card_ending_no,
'card_type' => $card_type
            ));
}
}


if(!$result->success){
foreach($result->errors->deepAll() AS $error) {
  $all_errors .= $error->message . "<br>";
  $all_errors_mob .= $error->message . "\r\n";
}

if(!$all_errors) $all_errors = $result->message;
if(!$all_errors_mob) $all_errors_mob = $result->message;

return (array(
                'success' => 0,
                'message' => $all_errors,
'message_mob' => $all_errors_mob
            ));

}

     }

*/


 public function addPaymentMethod_real($data){
$all_errors = '';
$all_errors_mob = '';

Braintree_Configuration::environment('production');
        Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
        Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
        Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');

$result = Braintree_PaymentMethod::create($data);

if(!$result->success){

foreach($result->errors->deepAll() AS $error) {
  $all_errors .= $error->message . "<br>";
  $all_errors_mob .= $error->message . "\r\n";
}

if(!$all_errors) $all_errors = $result->message;
if(!$all_errors_mob) $all_errors_mob = $result->message;

return (array(
                'success' => 0,
                'message' => $all_errors,
'message_mob' => $all_errors_mob
            ));

}

if($result->success){
$paytoken = $result->paymentMethod->token;

$card_ending_no = $result->paymentMethod->last4;
$card_type = $result->paymentMethod->cardType;
return (array(
                'success' => 1,
                'message' => 'payment method added',
'token' => $paytoken,
'card_ending_no' => $card_ending_no,
'card_type' => $card_type,
'masked_number' => $result->paymentMethod->maskedNumber
            ));
}

     }


public function updatePaymentMethod($token, $data){
$all_errors = '';
$all_errors_mob = '';
$result = Braintree_PaymentMethod::update($token, $data);
//print_r($result);
if(!$result->success){
foreach($result->errors->deepAll() AS $error) {
  $all_errors .= $error->message . "<br>";
  $all_errors_mob .= $error->message . "\r\n";
}

if(!$all_errors) $all_errors = $result->message;
if(!$all_errors_mob) $all_errors_mob = $result->message;

return (array(
                'success' => 0,
                'message' => $all_errors,
'message_mob' => $all_errors_mob
            ));

}

if($result->success){
$paytoken = $result->paymentMethod->token;

$card_ending_no = $result->paymentMethod->last4;
$card_type = $result->paymentMethod->cardType;
return (array(
                'success' => 1,
                'message' => 'payment method updated',
'token' => $paytoken,
'card_ending_no' => $card_ending_no,
'card_type' => $card_type,
'masked_number' => $result->paymentMethod->maskedNumber
            ));
}

     }


public function updatePaymentMethod_real($token, $data){
Braintree_Configuration::environment('production');
        Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
        Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
        Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');

$all_errors = '';
$all_errors_mob = '';
$result = Braintree_PaymentMethod::update($token, $data);
//print_r($result);
if(!$result->success){
foreach($result->errors->deepAll() AS $error) {
  $all_errors .= $error->message . "<br>";
  $all_errors_mob .= $error->message . "\r\n";
}

if(!$all_errors) $all_errors = $result->message;
if(!$all_errors_mob) $all_errors_mob = $result->message;

return (array(
                'success' => 0,
                'message' => $all_errors,
'message_mob' => $all_errors_mob
            ));

}

if($result->success){
$paytoken = $result->paymentMethod->token;

$card_ending_no = $result->paymentMethod->last4;
$card_type = $result->paymentMethod->cardType;
return (array(
                'success' => 1,
                'message' => 'payment method updated',
'token' => $paytoken,
'card_ending_no' => $card_ending_no,
'card_type' => $card_type,
'masked_number' => $result->paymentMethod->maskedNumber
            ));
}

     }

     public function deletePaymentMethod($token){
     	$result = Braintree_PaymentMethod::delete($token);
	if($result->success){
	return (array(
                'success' => 1,
                'message' => 'payment method deleted'
            ));
	}
	else{
	return (array(
                'success' => 0,
                'message' => 'payment method not found'
            ));
	}

     }

public function deletePaymentMethod_real($token){

Braintree_Configuration::environment('production');
        Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
        Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
        Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');

     	$result = Braintree_PaymentMethod::delete($token);
	if($result->success){
	return (array(
                'success' => 1,
                'message' => 'payment method deleted'
            ));
	}
	else{
	return (array(
                'success' => 0,
                'message' => 'payment method not found'
            ));
	}

     }

    public function createSubscription($payment_method_token)
    {
        $result = Braintree_Subscription::create(array(
            'paymentMethodToken' => $payment_method_token,
            'planId' => 'fxtb'
        ));


        if ($result->success) {

            return (array(
                'success' => 1,
                'subscription_id' => $result->subscription->id,
                'subscription_status' => $result->subscription->status
            ));
            /*
             eg:Array
         (
             [success] => 1
             [subscription_id] => 59btqg
             [subscription_status] => Active
         )

            */

        } else {
            return (array(
                'success' => 0,
                'validation_errors' => $result->errors->deepAll(),

            ));

        }

    }


    public function createSubMerchant($data)
    {
        $result = Braintree_MerchantAccount::create($data);

        if ($result->success) {

            return (array(
                'success' => 1,
                'sub_merchant_id' => $result->merchantAccount->id,
                'status' => $result->merchantAccount->status,
                'currency_code' => $result->merchantAccount->currencyIsoCode
            ));
            /*
              eg:Array
           (
           [success] => 1
           [sub_merchant_id] => jane_doe_instant5
           [status] => pending
           [currency_code] => USD
           )
           /*  firstname=>approve_me
           Array
             (
             [success] => 1
             [sub_merchant_id] => approve_me_doe_instant
             [status] => pending
             [currency_code] => USD
             )
            */

        } else {
            return (array(
                'success' => 0,
                'errors' => $result->errors->deepAll(),

            ));
            /* eg:
              Array
          (
          [success] => 0
          [errors] => Array
              (
              [0] => Braintree_Error_Validation Object
                  (
                  [_attribute:Braintree_Error_Validation:private] => mobilePhone
                  [_code:Braintree_Error_Validation:private] => 82683
                  [_message:Braintree_Error_Validation:private] => Funding mobile phone is invalid.
                  )

              )

          )

            */

        }

    }


  public function createSubMerchant_real($data)
    {

Braintree_Configuration::environment('production');
        Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
        Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
        Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');

        $result = Braintree_MerchantAccount::create($data);

        if ($result->success) {

            return (array(
                'success' => 1,
                'sub_merchant_id' => $result->merchantAccount->id,
                'status' => $result->merchantAccount->status,
                'currency_code' => $result->merchantAccount->currencyIsoCode
            ));
            /*
              eg:Array
           (
           [success] => 1
           [sub_merchant_id] => jane_doe_instant5
           [status] => pending
           [currency_code] => USD
           )
           /*  firstname=>approve_me
           Array
             (
             [success] => 1
             [sub_merchant_id] => approve_me_doe_instant
             [status] => pending
             [currency_code] => USD
             )
            */

        } else {
            return (array(
                'success' => 0,
                'errors' => $result->errors->deepAll(),

            ));
            /* eg:
              Array
          (
          [success] => 0
          [errors] => Array
              (
              [0] => Braintree_Error_Validation Object
                  (
                  [_attribute:Braintree_Error_Validation:private] => mobilePhone
                  [_code:Braintree_Error_Validation:private] => 82683
                  [_message:Braintree_Error_Validation:private] => Funding mobile phone is invalid.
                  )

              )

          )

            */

        }

    }

    public function updateSubMerchant($sub_merchant_id, $data)
    {

try {
            $result = Braintree_MerchantAccount::update($sub_merchant_id, $data);

if ($result->success) {

            return (array(
                'success' => 1,
                'sub_merchant_id' => $result->merchantAccount->id,
                'status' => $result->merchantAccount->status,

            ));

        } else {
            return (array(
                'success' => 0,
                'errors' => $result->errors->deepAll(),

            ));

        }

        } catch (Exception $e) {
            return (array(
                'success' => 0,
                'message' => 'error in updating submerchant',

            ));
        }

    }


public function updateSubMerchant_real($sub_merchant_id, $data)
    {


Braintree_Configuration::environment('production');
        Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
        Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
        Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');

try {
            $result = Braintree_MerchantAccount::update($sub_merchant_id, $data);

if ($result->success) {

            return (array(
                'success' => 1,
                'sub_merchant_id' => $result->merchantAccount->id,
                'status' => $result->merchantAccount->status,

            ));

        } else {
            return (array(
                'success' => 0,
                'errors' => $result->errors->deepAll(),

            ));

        }

        } catch (Exception $e) {
            return (array(
                'success' => 0,
                'message' => 'error in updating submerchant',

            ));
        }

    }


public function getsubmerchantbyid($submerchant_id)
    {


        try {
            $merchantAccount = Braintree_MerchantAccount::find($submerchant_id);

            return ($merchantAccount);
        } catch (Exception $e) {
            return (array(
                'success' => 0,
                'message' => 'Submerchant with ' . $submerchant_id . " is not found",

            ));
        }



        return ($merchantAccount);
    }


public function getsubmerchantbyid_real($submerchant_id)
    {

Braintree_Configuration::environment('production');
        Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
        Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
        Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');

        try {
            $merchantAccount = Braintree_MerchantAccount::find($submerchant_id);

            return ($merchantAccount);
        } catch (Exception $e) {
            return (array(
                'success' => 0,
                'message' => 'Submerchant with ' . $submerchant_id . " is not found",

            ));
        }



        return ($merchantAccount);
    }

    public function transactToSubMerchant($data)
    {
        $result = Braintree_Transaction::sale($data);

        /*
        echo "<pre>";
        print_r($result);
        echo "</pre>";
        */
        if ($result->success) {

            return (array(
                'success' => 1,
                'transaction_id' => $result->transaction->id,
                'amount' => $result->transaction->amount,
                'status' => $result->transaction->status,
                'type' => $result->transaction->type,
                'service_fee' => $result->transaction->serviceFeeAmount,
                'currency_code' => $result->transaction->currencyIsoCode,
                'escrow_status' => $result->transaction->escrowStatus,

            ));
            /*
             eg:Array
       (
           [success] => 1
           [transaction_id] => 4x7wdg
           [amount] => 100.00
           [status] => submitted_for_settlement
           [currency_code] => USD
       )

            */

        } else {

$all_errors = '';
$all_errors_mob = '';

foreach($result->errors->deepAll() AS $error) {
  $all_errors .= $error->message . "<br>";
$all_errors_mob .= $error->message . "\r\n";
}

if(!$all_errors) $all_errors = $result->message;
if(!$all_errors_mob) $all_errors_mob = $result->message;

if($result->transaction->id){
     return (array(
                'success' => 0,
                'message' => $all_errors,
 'message_mob' => $all_errors_mob,
 'transaction_id' => $result->transaction->id

            )); 
}
else{
    return (array(
                'success' => 0,
                'message' => $all_errors,
 'message_mob' => $all_errors_mob,
 'transaction_id' => ''

            ));  
}
          

        }

    }



 public function transactToSubMerchant_real($data)
    {

Braintree_Configuration::environment('production');
        Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
        Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
        Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');

try{
$result = Braintree_Transaction::sale($data);


        //echo "<pre>";
        //print_r($result);
        //echo "</pre>";

        if ($result->success) {

            return (array(
                'success' => 1,
                'transaction_id' => $result->transaction->id,
                'amount' => $result->transaction->amount,
                'status' => $result->transaction->status,
                'type' => $result->transaction->type,
                'service_fee' => $result->transaction->serviceFeeAmount,
                'currency_code' => $result->transaction->currencyIsoCode,
                'escrow_status' => $result->transaction->escrowStatus,

            ));


        } else {

$all_errors = '';
$all_errors_mob = '';

foreach($result->errors->deepAll() AS $error) {
  $all_errors .= $error->message . "<br>";
$all_errors_mob .= $error->message . "\r\n";

}

if(!$all_errors) $all_errors = $result->message;
if(!$all_errors_mob) $all_errors_mob = $result->message;

if($result->transaction->id){
     return (array(
                'success' => 0,
                'message' => $all_errors,
 'message_mob' => $all_errors_mob,
 'transaction_id' => $result->transaction->id

            )); 
}
else{
    return (array(
                'success' => 0,
                'message' => $all_errors,
 'message_mob' => $all_errors_mob,
 'transaction_id' => ''

            ));  
}



 }
 }catch (Exception $e) {
return (array(
                'success' => 0,
                'message' => 'Error in processing payment'
 ));
}






    }



    public function releaseFromEscrow($transaction_id)
    {

        $result = Braintree_Transaction::releaseFromEscrow($transaction_id);

        if ($result->success) {
          return (array(
                'success' => 1,
                'transaction_id' => $result->transaction->id,
                'escrow_status' => $result->transaction->escrowStatus));
        } else {

foreach($result->errors->deepAll() AS $error) {
  $all_errors .= $error->message . "<br>";
$all_errors_mob .= $error->message . "\r\n";
}

if(!$all_errors) $all_errors = $result->message;
if(!$all_errors_mob) $all_errors_mob = $result->message;

            return (array(
                'success' => 0,
                'message' => $all_errors,
 'message_mob' => $all_errors_mob,

            ));
        }
    }


public function releaseFromEscrow_real($transaction_id)
    {

Braintree_Configuration::environment('production');
        Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
        Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
        Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');

        $result = Braintree_Transaction::releaseFromEscrow($transaction_id);

        if ($result->success) {
          
return (array(
                'success' => 1,
                'transaction_id' => $result->transaction->id,
                'escrow_status' => $result->transaction->escrowStatus));
        } else {

foreach($result->errors->deepAll() AS $error) {
  $all_errors .= $error->message . "<br>";
$all_errors_mob .= $error->message . "\r\n";
}

if(!$all_errors) $all_errors = $result->message;
if(!$all_errors_mob) $all_errors_mob = $result->message;

            return (array(
                'success' => 0,
                'message' => $all_errors,
 'message_mob' => $all_errors_mob,

            ));
        }
    }


 public function submitforsettlement($transaction_id)
    {

        $result =  Braintree_Transaction::submitForSettlement($transaction_id);

        if ($result->success) {
            return (array(
                'success' => 1

            ));
        } else {
            return (array(
                'success' => 0,
                'errors' => $result->errors->deepAll(),

            ));
        }
    }

 public function submitforsettlement_real($transaction_id)
    {

Braintree_Configuration::environment('production');
        Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
        Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
        Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');

        $result =  Braintree_Transaction::submitForSettlement($transaction_id);

        if ($result->success) {
            return (array(
                'success' => 1

            ));
        } else {
            return (array(
                'success' => 0,
                'errors' => $result->errors->deepAll(),

            ));
        }
    }

 public function void($transaction_id)
    {

        $result =  Braintree_Transaction::void($transaction_id);

        if ($result->success) {
            return (array(
                'success' => 1

            ));
        } else {

foreach($result->errors->deepAll() AS $error) {
  $all_errors .= $error->message . "<br>";
$all_errors_mob .= $error->message . "\r\n";
}

if(!$all_errors) $all_errors = $result->message;
if(!$all_errors_mob) $all_errors_mob = $result->message;

            return (array(
                'success' => 0,
               'message' => $all_errors,
 'message_mob' => $all_errors_mob,

            ));
        }
    }

 public function void_real($transaction_id)
    {

Braintree_Configuration::environment('production');
        Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
        Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
        Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');

        $result =  Braintree_Transaction::void($transaction_id);

        if ($result->success) {
            return (array(
                'success' => 1

            ));
        } else {

foreach($result->errors->deepAll() AS $error) {
  $all_errors .= $error->message . "<br>";
$all_errors_mob .= $error->message . "\r\n";
}

if(!$all_errors) $all_errors = $result->message;
if(!$all_errors_mob) $all_errors_mob = $result->message;

            return (array(
                'success' => 0,
                'message' => $all_errors,
 'message_mob' => $all_errors_mob,

            ));
        }
    }


public function refund($transaction_id, $amount = 0)
    {

        if($amount > 0) $result =  Braintree_Transaction::refund($transaction_id, $amount);
        else $result =  Braintree_Transaction::refund($transaction_id); 

        if ($result->success) {
            return (array(
                'success' => 1,
                'transaction_id' => $result->transaction->id

            ));
        } else {

foreach($result->errors->deepAll() AS $error) {
  $all_errors .= $error->message . "<br>";
$all_errors_mob .= $error->message . "\r\n";
}

if(!$all_errors) $all_errors = $result->message;
if(!$all_errors_mob) $all_errors_mob = $result->message;

            return (array(
                'success' => 0,
               'message' => $all_errors,
 'message_mob' => $all_errors_mob,

            ));
        }
    }


public function refund_real($transaction_id, $amount = 0)
    {

Braintree_Configuration::environment('production');
        Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
        Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
        Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');

        
         if($amount > 0) $result =  Braintree_Transaction::refund($transaction_id, $amount);
        else $result =  Braintree_Transaction::refund($transaction_id); 

        if ($result->success) {
            return (array(
                'success' => 1,
                'transaction_id' => $result->transaction->id

            ));
        } else {

foreach($result->errors->deepAll() AS $error) {
  $all_errors .= $error->message . "<br>";
$all_errors_mob .= $error->message . "\r\n";
}

if(!$all_errors) $all_errors = $result->message;
if(!$all_errors_mob) $all_errors_mob = $result->message;


            return (array(
                'success' => 0,
                'message' => $all_errors,
 'message_mob' => $all_errors_mob,

            ));
        }
    }

    public function getTransactionById($transaction_id)
    {


        try {
            $transaction = Braintree_Transaction::find($transaction_id);
            return (array(
                'success' => 1,
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,

                'type' => $transaction->type,
 'status' => $transaction->status,
                'escrow_status' => $transaction->escrowStatus,
 'card_no' => $transaction->creditCardDetails->maskedNumber,
'exp_mo' => $transaction->creditCardDetails->expirationMonth,
'exp_yr' => $transaction->creditCardDetails->expirationYear,
'cardholder_name' => $transaction->creditCardDetails->cardholderName,
'cardtype_img' => $transaction->creditCardDetails->imageUrl

            ));
        } catch (Exception $e) {
            return (array(
                'success' => 0,
                'message' => 'Customer with ' . $transaction_id . " is not found"
            ));
        }


    }


public function getTransactionById_real($transaction_id)
    {

Braintree_Configuration::environment('production');
        Braintree_Configuration::merchantId('74zsnfqy5svgpvjv');
        Braintree_Configuration::publicKey('7gg5kfvkx8w5fcx8');
        Braintree_Configuration::privateKey('579e6af0c752079c2f9596c838191327');

        try {
            $transaction = Braintree_Transaction::find($transaction_id);
            return (array(
                'success' => 1,
'transaction_id' => $transaction->id,
                'amount' => $transaction->amount,

                'type' => $transaction->type,
 'status' => $transaction->status,
                'escrow_status' => $transaction->escrowStatus,
 'card_no' => $transaction->creditCardDetails->maskedNumber,
'exp_mo' => $transaction->creditCardDetails->expirationMonth,
'exp_yr' => $transaction->creditCardDetails->expirationYear,
'cardholder_name' => $transaction->creditCardDetails->cardholderName,
'cardtype_img' => $transaction->creditCardDetails->imageUrl

            ));
        } catch (Exception $e) {
            return (array(
                'success' => 0,
                'message' => 'Customer with ' . $transaction_id . " is not found"
            ));
        }


    }

    public function createCard($data)
    {
        $result = Braintree_CreditCard::create($data);

        if ($result->success) {

            return (array('success' => 1,
                'payment_method_token' => $result->creditCard->token
            ));

        } else {
            return (array(
                'success' => 0,
                'validation_errors' => $result->errors->deepAll(),

            ));

        }
    }


    public function cloneTransaction($transaction_id, $amount)
    {
        $result = Braintree_Transaction::cloneTransaction($transaction_id, array(
            'amount' => $amount,

            'options' => array(
                'submitForSettlement' => true,

            )
        ));


        if ($result->success) {


            return (array('success' => 1,
                'transaction_id' => $result->transaction->id,
                'amount' => $result->transaction->amount,
                'currency_code' => $result->transaction->currencyIsoCode,
                'type' => $result->transaction->type,
                'escrow_status' => $result->transaction->escrowStatus,
                'service_fee' => $result->transaction->serviceFeeAmount
            ));

        } else {


            if (count($result->errors->deepAll()) > 0) {
                return (array(
                    'success' => 0,
                    'transaction_clone_errors' => $result->errors->deepAll(),

                ));
            } else {
                return (array(
                    'success' => 0,
                    'transaction_clone_errors' => $result->message,

                ));
            }

        }

    }

    public function getMasterAccountId()
    {
        return ($this->MERCHANT_ACCOUNT_ID);
    }

    public function verifyWebHookNotification()
    {
        if (isset($_GET["bt_challenge"])) {

            echo(Braintree_WebhookNotification::verify($_GET["bt_challenge"]));

        } else {
            return (false);
        }
    }

    public function parseWebHookNotification()
    {
        if (
            isset($_POST["bt_signature"]) &&
            isset($_POST["bt_payload"])
        ) {
            $webhookNotification = Braintree_WebhookNotification::parse(
                $_POST["bt_signature"], $_POST["bt_payload"]
            );

            return ($webhookNotification);
            /*
             $message =
             "[Webhook Received " . $webhookNotification->timestamp->format('Y-m-d H:i:s') . "] "
             . "Kind: " . $webhookNotification->kind . " | "
             . "Subscription: " . $webhookNotification->subscription->id . "\n";

             file_put_contents("/tmp/webhook.log", $message, FILE_APPEND);
           */
        } else
            return (false);


    }

}

/*
                 eg:Array
(
  [success] => 0
  [message] => Amount must be greater than zero.
Credit card type is not accepted by this merchant account.
Credit card number is not an accepted test number.
  [validation_errors] => Array
      (
          [0] => Braintree_Error_Validation Object
              (
                  [_attribute:Braintree_Error_Validation:private] => amount
                  [_code:Braintree_Error_Validation:private] => 81531
                  [_message:Braintree_Error_Validation:private] => Amount must be greater than zero.
              )

          [1] => Braintree_Error_Validation Object
              (
                  [_attribute:Braintree_Error_Validation:private] => number
                  [_code:Braintree_Error_Validation:private] => 81703
                  [_message:Braintree_Error_Validation:private] => Credit card type is not accepted by this merchant account.
              )

          [2] => Braintree_Error_Validation Object
              (
                  [_attribute:Braintree_Error_Validation:private] => number
                  [_code:Braintree_Error_Validation:private] => 81717
                  [_message:Braintree_Error_Validation:private] => Credit card number is not an accepted test number.
              )

      )

)
               */