<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

class SMSComponent extends Component{
    public function sendSMS($to, $msg) { // Aldeamo
        $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
        <sending>
        <authentication>
        <username>SistemcobroWS</username>
        <password>1877*Mundo</password>
        </authentication>
        <country>57</country>
        <recipients>
            <sms>
                <mobile>".$to."</mobile>
                <message>".$msg."</message>
                <operator></operator>
            </sms>
        </recipients>
        </sending>";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://apismsi.aldeamo.com/smsr/r/hcws/smsSendPost/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER =>  0,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $xml,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/xml",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
    }
    public function sendSMS_old($to, $msg) { // infobip
        $username = 'Pidefarma';
        $password = 'sMsco2015';
        $to = '57'.$to;
        $text = $msg;
        $from = 'app';
        // URL for sending request
        $postUrl = "https://api.infobip.com/sms/1/text/advanced";
        // creating an object for sending SMS
        $destination = [
            //"messageId" => $messageId,
            "to" => $to
        ];
        $message = [
            "Campaign" => 'analytics',
            //"from" => $from,
            "destinations" => [$destination],
            "text" => $text,
            //"notifyUrl" => $notifyUrl,
            //"notifyContentType" => $notifyContentType,
            //"callbackData" => $callbackData
        ];
        $postData = ["messages" => [$message]];
        // encoding object
        $postDataJson = json_encode($postData);
        $ch = curl_init();
        $header = array("Content-Type:application/json", "Accept:application/json");
        curl_setopt($ch, CURLOPT_URL, $postUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataJson);
        // response of the POST request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $responseBody = json_decode($response);
        curl_close($ch);
    }
}
