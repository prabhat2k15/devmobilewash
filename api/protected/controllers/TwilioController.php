<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');
class TwilioController extends Controller
{
    protected $pccountSid = TWILIO_SID;
    protected $authToken = TWILIO_AUTH_TOKEN;
    protected $from = '+13108703052 ';
    protected $callbackurl = ROOT_URL.'/api/complete_call.php?fromnumber=+';
    protected $apiurl = 'https://api.twilio.com';
    protected $appSid = 'APfd976a6070947f3d1368191eba84ed70';

    public function actionmakeacall()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$api_token = Yii::app()->request->getParam('api_token');
$t1 = Yii::app()->request->getParam('t1');
$t2 = Yii::app()->request->getParam('t2');
$user_type = Yii::app()->request->getParam('user_type');
$user_id = Yii::app()->request->getParam('user_id');

$token_check = $this->verifyapitoken( $api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS );

if(!$token_check){
 $json = array(
                    'result'=> 'false',
                    'response'=> 'Invalid request'
                );
 echo json_encode($json);
 die();
}
        
        $call_id = Yii::app()->request->getParam('tonumber');
        $fromnumber = Yii::app()->request->getParam('fromnumber');
	$wash_request_id = '';
	if(Yii::app()->request->getParam('wash_request_id')) {
	    $wash_request_id = Yii::app()->request->getParam('wash_request_id');
	     if(AES256CBC_STATUS == 1){
$wash_request_id = $this->aes256cbc_crypt( $wash_request_id, 'd', AES256CBC_API_PASS );
}
	}
        $url = $this->callbackurl.$fromnumber;
        $newurl = preg_replace( '/\s+/', '', $url ); 
        $result  = 'false';
        $json    = array();

		   
        if (!empty($call_id) && !empty($fromnumber)) {

			$validatephone_to = strlen($call_id);
			$validatephone_from = strlen($fromnumber);

	       if($validatephone_to >=10 || $validatephone_to >= 10){
            
            $this->layout = "xmlLayout";
          
            require_once(ROOT_WEBFOLDER.'/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
                require_once(ROOT_WEBFOLDER.'/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');
            
            
            /* Number you wish to call */
            $to = $call_id;
            
            /* Instantiate a new Twilio Rest Client */
            try {
	    $http = new Services_Twilio_TinyHttp($this->apiurl, array(
                'curlopts' => array(
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => 2
                )
            ));
            
            $client = new Services_Twilio($this->pccountSid, $this->authToken, date('Y-m-d'), $http);
            
            
            /* make Twilio REST request to initiate outgoing call */
            $call = $client->account->calls->create($this->from, $to, $newurl);
	    //$call = $client->account->calls->create($fromnumber, $to, $newurl);

			//exit;
            $capability = new Services_Twilio_Capability($this->pccountSid, $this->authToken);
            $capability->allowClientOutgoing($this->appSid);
            $token = $capability->generateToken();
	    		     }catch (Services_Twilio_RestException $e) {
            echo  $e;
}

			if($token !=""){
            $data = array(
                'token' => $token
            );
		
		if($wash_request_id){
		   $cust_details = Customers::model()->findByAttributes(array('contact_number' => $fromnumber));
		$agent_details = Agents::model()->findByAttributes(array('phone_number' => $fromnumber));
		$wash_id_check = Washingrequests::model()->findByPk($wash_request_id);
		
		if(count($cust_details)){
		    $agent_det = Agents::model()->findByPk($wash_id_check->agent_id);
		 $logdata = array(
                        'agent_id'=> $wash_id_check->agent_id,
                        'wash_request_id'=> $wash_request_id,
			'agent_company_id'=> $agent_det->real_washer_id,
                        'action'=> 'customercall',
                        'action_date'=> date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $logdata);
		}
		
		if(count($agent_details)){
		 $logdata = array(
                        'agent_id'=> $wash_id_check->agent_id,
                        'wash_request_id'=> $wash_request_id,
			'agent_company_id'=> $agent_details->real_washer_id,
                        'action'=> 'agentcall',
                        'action_date'=> date('Y-m-d H:i:s'));

                    Yii::app()->db->createCommand()->insert('activity_logs', $logdata);
		} 
		}
		
		  
		    
			}else{

				$data = array(
                'result' => 'false',
                'response' => 'phone numbers are wrong, Please check.'
                
            );


			}


			}else{

				$data = array(
                'result' => 'false',
                'response' => 'Wrong phone number(s). Please add country code with phone number using + sign'
                
            );

			}
            
          } else {
            $data = array(
                'result' => 'false',
                'response' => 'to and from phone number missing'
                
            );
            
        }
        
		echo json_encode($data);
		/*spl_autoload_register(array(
                'YiiBase',
                'autoload'
            ));*/
        exit;
        
    }
    
    public function actionGenerateToken()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$api_token = Yii::app()->request->getParam('api_token');
$t1 = Yii::app()->request->getParam('t1');
$t2 = Yii::app()->request->getParam('t2');
$user_type = Yii::app()->request->getParam('user_type');
$user_id = Yii::app()->request->getParam('user_id');

$token_check = $this->verifyapitoken( $api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS );

if(!$token_check){
 $json = array(
                    'result'=> 'false',
                    'response'=> 'Invalid request'
                );
 echo json_encode($json);
 die();
}
        
        //$call_id = Yii::app()->request->getParam('phonenumber');
        $json    = array();
        
            include 'Services/Twilio/Capability.php';
            $accountSid = TWILIO_SID;
            $authToken  = TWILIO_AUTH_TOKEN;

            // put your Twilio Application Sid here
            $appSid     = 'APfd976a6070947f3d1368191eba84ed70';

            $capability = new Services_Twilio_Capability($accountSid, $authToken);
            $capability->allowClientOutgoing($appSid);
            $capability->allowClientIncoming('jenny');
            $token = $capability->generateToken();
            echo "<pre>";
            print_r($token);
            echo "<pre>";
            
            
        
        
    }

public function actionDeleteMessage()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$api_token = Yii::app()->request->getParam('api_token');
$t1 = Yii::app()->request->getParam('t1');
$t2 = Yii::app()->request->getParam('t2');
$user_type = Yii::app()->request->getParam('user_type');
$user_id = Yii::app()->request->getParam('user_id');

$token_check = $this->verifyapitoken( $api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS );

if(!$token_check){
 $json = array(
                    'result'=> 'false',
                    'response'=> 'Invalid request'
                );
 echo json_encode($json);
 die();
}
		$id = Yii::app()->request->getParam('id');
		$model=new Messges;
		$delmessage = Messges::model()->deleteAll('id=:id', array(':id'=>$id));
		if($delmessage){
					    $result= 'true';
						$response= 'agents deleted';
					}
					$json= array(
			'result'=> $result,
			'response'=> $response,
		);
					echo json_encode($json);
         die();
	}


public function actionsendsms()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$api_token = Yii::app()->request->getParam('api_token');
$t1 = Yii::app()->request->getParam('t1');
$t2 = Yii::app()->request->getParam('t2');
$user_type = Yii::app()->request->getParam('user_type');
$user_id = Yii::app()->request->getParam('user_id');

$token_check = $this->verifyapitoken( $api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS );

if(!$token_check){
 $json = array(
                    'result'=> 'false',
                    'response'=> 'Invalid request'
                );
 echo json_encode($json);
 die();
}
		   
		   $to_num = Yii::app()->request->getParam('tonumber');
		   $from_num = Yii::app()->request->getParam('fromnumber');
		   $message = Yii::app()->request->getParam('message');
		   $media = Yii::app()->request->getParam('media');
           $to_num = urlencode($to_num);
		  // $message = urlencode($message);
		   
		   $result  = 'false';
			$json    = array();
           
            $this->layout = "xmlLayout";

			
           require_once(ROOT_WEBFOLDER.'/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
            require_once(ROOT_WEBFOLDER.'/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');
           
            /* Instantiate a new Twilio Rest Client */

			$account_sid = TWILIO_SID; 
			$auth_token = TWILIO_AUTH_TOKEN; 
			$client = new Services_Twilio($account_sid, $auth_token);
			
			if(!empty($media)){
			 try {
			$sendmessage = $client->account->messages->create(array( 
			    'To' =>  $to_num, 
				'From' => $from_num,
				'Body' => $message, 
				'MediaUrl' => $media,
			));
			 }catch (Services_Twilio_RestException $e) {
      $data = array(
                'result' => 'false',
                'response' => 'phone numbers are wrong, Please check.'
                
            );
}
			}else{
			     try {
			$sendmessage = $client->account->messages->create(array( 
			    'To' =>  $to_num, 
				'From' => $from_num,
				'Body' => $message,
			));
			     }catch (Services_Twilio_RestException $e) {
         $data = array(
                'result' => 'false',
                'response' => 'phone numbers are wrong, Please check.'
                
            );
}
			}
           
			

			
           if($sendmessage !=""){
            $data = array(
                'sid' => $sendmessage->sid,
				'status'=>$sendmessage->status,
            );
			}else{

				$data = array(
                'result' => 'false',
                'response' => 'phone numbers are wrong, Please check.'
                
            );


			}

        echo json_encode($data);

        exit;
        
    }
    
public function actionReportChange()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$api_token = Yii::app()->request->getParam('api_token');
$t1 = Yii::app()->request->getParam('t1');
$t2 = Yii::app()->request->getParam('t2');
$user_type = Yii::app()->request->getParam('user_type');
$user_id = Yii::app()->request->getParam('user_id');

$token_check = $this->verifyapitoken( $api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS );

if(!$token_check){
 $json = array(
                    'result'=> 'false',
                    'response'=> 'Invalid request'
                );
 echo json_encode($json);
 die();
}

		$id = Yii::app()->request->getParam('id');
		$messagedata= array(
					'report'=> 'sent',
				);
				
		$model=new Messges;
		$update_message = Messges::model()->updateAll($messagedata,'id=:id',array(':id'=>$id));
			$result = 'true';
            $response = 'updated successfully';
            $json = array(
                'result'=> $result,
                'response'=> $response
            );
			echo json_encode($json);
			die();
		
		
	}

public function actiongetmessges()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$api_token = Yii::app()->request->getParam('api_token');
$t1 = Yii::app()->request->getParam('t1');
$t2 = Yii::app()->request->getParam('t2');
$user_type = Yii::app()->request->getParam('user_type');
$user_id = Yii::app()->request->getParam('user_id');

$token_check = $this->verifyapitoken( $api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS );

if(!$token_check){
 $json = array(
                    'result'=> 'false',
                    'response'=> 'Invalid request'
                );
 echo json_encode($json);
 die();
}

		 $message =  Yii::app()->db->createCommand("SELECT * FROM messages ORDER BY id DESC")->queryAll();
        $messagedetails = array();
        foreach($message as $messages)
        {
            
             $json = array();
             $json['to'] =  $messages['to'];
             $json['phone'] =  $messages['phone'];
             $json['message'] =  $messages['message'];
             $json['media'] =  $messages['media'];
             $json['id'] =  $messages['id'];
             $json['report'] =  $messages['report'];
             $messagedetails[] = $json;
        }
        $messagereturn['messages'] = $messagedetails;
        echo json_encode($messagereturn, JSON_PRETTY_PRINT);

         exit;
	}

public function actionMessges()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$api_token = Yii::app()->request->getParam('api_token');
$t1 = Yii::app()->request->getParam('t1');
$t2 = Yii::app()->request->getParam('t2');
$user_type = Yii::app()->request->getParam('user_type');
$user_id = Yii::app()->request->getParam('user_id');

$token_check = $this->verifyapitoken( $api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS );

if(!$token_check){
 $json = array(
                    'result'=> 'false',
                    'response'=> 'Invalid request'
                );
 echo json_encode($json);
 die();
}

		$to = Yii::app()->request->getParam('to');
		$phone = Yii::app()->request->getParam('phone');
		$message = Yii::app()->request->getParam('message');
		$media = Yii::app()->request->getParam('media');
		$messagedata= array(
					'to'=> $to,
					'phone'=> $phone,
					'message'=> $message,
					'media'=> $media,
				);
				
		$model=new Messges;
		
		$model->attributes= $messagedata;
		if($model->save(false)){
			$messagedataid = Yii::app()->db->getLastInsertID();
			$result= 'true';
			$response= 'Message successfully store';
			$json= array(
						'result'=> $result,
						'response'=> $response,
						'messagedataid'=> $messagedataid,
					);
					echo json_encode($json);
					die();
		}else{
			$result= 'false';
			$response= 'Not store';
			$json= array(
						'result'=> $result,
						'response'=> $response,
					);
					echo json_encode($json);
					die();
		}
		
	}	


public function actionEditMessges()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$api_token = Yii::app()->request->getParam('api_token');
$t1 = Yii::app()->request->getParam('t1');
$t2 = Yii::app()->request->getParam('t2');
$user_type = Yii::app()->request->getParam('user_type');
$user_id = Yii::app()->request->getParam('user_id');

$token_check = $this->verifyapitoken( $api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS );

if(!$token_check){
 $json = array(
                    'result'=> 'false',
                    'response'=> 'Invalid request'
                );
 echo json_encode($json);
 die();
}

		$to = Yii::app()->request->getParam('to');
		$phone = Yii::app()->request->getParam('phone');
		$message = Yii::app()->request->getParam('message');
		$media = Yii::app()->request->getParam('media');
		$id = Yii::app()->request->getParam('id');
		$messagedata= array(
					'to'=> $to,
					'phone'=> $phone,
					'message'=> $message,
					'media'=> $media,
				);
				
		$model=new Messges;
		$update_message = Messges::model()->updateAll($messagedata,'id=:id',array(':id'=>$id));
			$result = 'true';
            $response = 'update successfully';
            $json = array(
                'result'=> $result,
                'response'=> $response
            );
			echo json_encode($json);
			die();
		
		
	}

public function actiongetsinglemessage()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$api_token = Yii::app()->request->getParam('api_token');
$t1 = Yii::app()->request->getParam('t1');
$t2 = Yii::app()->request->getParam('t2');
$user_type = Yii::app()->request->getParam('user_type');
$user_id = Yii::app()->request->getParam('user_id');

$token_check = $this->verifyapitoken( $api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS );

if(!$token_check){
 $json = array(
                    'result'=> 'false',
                    'response'=> 'Invalid request'
                );
 echo json_encode($json);
 die();
}

		$id = Yii::app()->request->getParam('id');
		$message =  Yii::app()->db->createCommand("SELECT * FROM messages WHERE id=:id ")->bindValue(':id', $id, PDO::PARAM_STR)->queryAll();
		
        $to = $message[0]['to'];
        $phone = $message[0]['phone'];
        $messages = $message[0]['message'];
        $media = $message[0]['media'];
        $json = array(
                'to'=> $to,
                'phone'=> $phone,
                'message'=> $messages,
                'media'=> $media
            );
             echo json_encode($json);
             exit;
	}

 public function actiongetreplysms()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$api_token = Yii::app()->request->getParam('api_token');
$t1 = Yii::app()->request->getParam('t1');
$t2 = Yii::app()->request->getParam('t2');
$user_type = Yii::app()->request->getParam('user_type');
$user_id = Yii::app()->request->getParam('user_id');

$token_check = $this->verifyapitoken( $api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS );

if(!$token_check){
 $json = array(
                    'result'=> 'false',
                    'response'=> 'Invalid request'
                );
 echo json_encode($json);
 die();
}

            $number = Yii::app()->request->getParam('number');
            $result  = 'false';
            $json    = array();
            $this->layout = "xmlLayout";
            spl_autoload_unregister(array(
                'YiiBase',
                'autoload'
            ));
            
            require('Services/Twilio.php');
            require('Services/Twilio/Capability.php');
           
            /* Instantiate a new Twilio Rest Client */

            $account_sid = TWILIO_SID; 
            $auth_token = TWILIO_AUTH_TOKEN; 
            $client = new Services_Twilio($account_sid, $auth_token);
           $data = array();
            foreach ($client->account->sms_messages as $sms) {
                if($sms->direction == 'inbound' && $sms->status == 'received' && $sms->to == $number){
                    $json = array();
                     $json['to'] =  $sms->to;
                     $json['from'] =  $sms->from;
                     $json['message'] =  $sms->body;
                     $json['date'] =  $sms->date_sent;
                     $data[] = $json;
                    
                }
                $messagedata['messages'] = $data;
    
}
           spl_autoload_register(array(
                'YiiBase',
                'autoload'
            ));
            echo json_encode($messagedata, JSON_PRETTY_PRINT);
        exit;
        
    }


public function actiongetreplynumber()
    {  

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$api_token = Yii::app()->request->getParam('api_token');
$t1 = Yii::app()->request->getParam('t1');
$t2 = Yii::app()->request->getParam('t2');
$user_type = Yii::app()->request->getParam('user_type');
$user_id = Yii::app()->request->getParam('user_id');

$token_check = $this->verifyapitoken( $api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS );

if(!$token_check){
 $json = array(
                    'result'=> 'false',
                    'response'=> 'Invalid request'
                );
 echo json_encode($json);
 die();
}
     
            $result  = 'false';
            $json    = array();
           
            $this->layout = "xmlLayout";
            spl_autoload_unregister(array(
                'YiiBase',
                'autoload'
            ));
            
            require('Services/Twilio.php');
            require('Services/Twilio/Capability.php');
           
            /* Instantiate a new Twilio Rest Client */

            $account_sid = TWILIO_SID; 
            $auth_token = TWILIO_AUTH_TOKEN; 
            $client = new Services_Twilio($account_sid, $auth_token);
            $phone = array();
            $i = 0;
            foreach ($client->account->incoming_phone_numbers as $number) {
                $i++;
                $phone[$i] = $number->phone_number;
}
        spl_autoload_register(array(
                'YiiBase',
                'autoload'
            ));
            $json= array(
            'result'=> $result,
            'response'=> $response,
            'phone'=> $phone,
        );
                    echo json_encode($json);
        
        exit;
        
    }
    
    public function actiongetallcalls()
    {

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$api_token = Yii::app()->request->getParam('api_token');
$t1 = Yii::app()->request->getParam('t1');
$t2 = Yii::app()->request->getParam('t2');
$user_type = Yii::app()->request->getParam('user_type');
$user_id = Yii::app()->request->getParam('user_id');

$token_check = $this->verifyapitoken( $api_token, $t1, $t2, $user_type, $user_id, AES256CBC_API_PASS );

if(!$token_check){
 $json = array(
                    'result'=> 'false',
                    'response'=> 'Invalid request'
                );
 echo json_encode($json);
 die();
}
		   
		   $to_num = Yii::app()->request->getParam('tonumber');
		   $from_num = Yii::app()->request->getParam('fromnumber');
		   $message = Yii::app()->request->getParam('message');
		   $media = Yii::app()->request->getParam('media');
           $to_num = urlencode($to_num);
		  // $message = urlencode($message);
		   
		   $result  = 'false';
			$json    = array();
           
            $this->layout = "xmlLayout";

			
           require_once(ROOT_WEBFOLDER.'/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio.php');
            require_once(ROOT_WEBFOLDER.'/public_html/api/protected/extensions/twilio/twilio-php/Services/Twilio/Capability.php');
           
            /* Instantiate a new Twilio Rest Client */

			$account_sid = TWILIO_SID; 
			$auth_token = TWILIO_AUTH_TOKEN; 
			
		
			/*$client = new Services_Twilio($account_sid, $auth_token);
//var_dump($client->account);
$calls = $client->account->calls->read();

foreach ($calls as $record) {
    print($record->sid);
}*/

$twilio = new Client($account_sid, $auth_token);

$calls = $twilio->calls->read();

foreach ($calls as $record) {
    print($record->sid);
}
           
			

        //echo json_encode($data);

        //exit;
        
    }


}