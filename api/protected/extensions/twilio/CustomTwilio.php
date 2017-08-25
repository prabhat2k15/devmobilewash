<?php

/**
 * CustomTwilio.php
 *
 * @author: Rohan M <rohan@thetatechnolabs.com>
 * Date: 4/1/2016
 * By phpstrom
 */
class CustomTwilio extends CApplicationComponent
{
    public $SID;

    public $CLIENT_TOKEN;

    public $VERSION;

    public function init()
    {

        require_once 'twilio-php/Services/Twilio.php';

    }

    public function makeCall($from_number,$to_number){

        $client = new Services_Twilio($this->SID, $this->CLIENT_TOKEN);

        try {
            // Initiate a new outbound call
            $call = $client->account->calls->create(
                $from_number, // The number of the phone initiating the call
                $to_number, // The number of the phone receiving call
                'http://demo.twilio.com/welcome/voice/' // The URL Twilio will request when the call is answered
            );
            echo 'Started call: ' . $call->sid;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }

    }
    public function GetCapabilitiesToken($customer_name) {
            $capability = new Services_Twilio_Capability($this->SID, $this->CLIENT_TOKEN);

            $capability->allowClientIncoming($customer_name);
            $capability->allowClientOutgoing($this->SID);
            return $capability->generateToken();


    }


}

