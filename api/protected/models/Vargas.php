<?php

class Vargas {

    public static function Obj() {
        return new self();
    }

    public function showFlashMsg($indexs) {
        if (!$indexs && !is_array($indexs))
            return;
        $msg = ' ';
        foreach ($indexs as $class => $index) {
            if (Yii::app()->user->hasFlash($index)) {
                $msg = '<div id="hide_msg" class=" ' . $class . '" style="font-size:17px; "> ' . Yii::app()->user->getFlash($index);
                $msg .= '</div>';
            }
        }

        echo $msg;
    }

    public function SendMail($uniqueMail, $from, $message, $subject, $layout = 'mail') {
        $mail = new YiiMailer();
        /* $mail->setSmtp('smtp.sendgrid.net', 587, '', true, 'apikey2', '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'); */

        $mail->setSmtp('smtp.gmail.com', 587, 'tls', true, 'info@mobilewash.com', '6DX!%jEGwW%caQIJBOwX');

        $mail->setFrom($from);
        $mail->setLayout($layout);
        $mail->setFrom($from, 'MobileWash');
        $mail->setTo($uniqueMail);
        //$mail->AddCC($cc);
        $mail->IsHTML(true);
        $mail->setSubject($subject);
        $mail->Body = $message;

        //$mail->setAttachment($filename);
        /*
          if($mail->send()) {
          echo "Message sent!";
          } else {
          echo "Mailer Error: " . $mail->ErrorInfo;
          }
         */
        return $mail->send();
    }

    public function getAdminEmail() {
        return Yii::app()->params['adminEmail'];
    }

    public function getAdminToEmail() {
        return Yii::app()->params['adminToEmail'];
    }

    public function getAdminFromEmail() {
        return Yii::app()->params['adminFromEmail'];
    }

    public function getAdminToEmailFeedBack() {
        return Yii::app()->params['AdminToEmailFeedBack'];
    }

    public function sendPushNotification($message, $trip_id, $customer_id, $driver_id, $deviceToken, $msg_type, $user_type = "customer") {
        
    }

    public function returnDateFormate() {
        return "Y-m-d h:i A";
    }

}

?>
