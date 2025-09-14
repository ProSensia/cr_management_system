<?php
// lib/send_whatsapp.php
require_once __DIR__ . '/../config.php';

function send_whatsapp_message($to_whatsapp_number, $message_text){
    if(!ENABLE_TWILIO){
        // For debugging: just log to file
        file_put_contents(__DIR__ . '/../logs/whatsapp.log', date('c') . " | TO: $to_whatsapp_number | MSG: $message_text\n", FILE_APPEND);
        return ['status'=>'debug','message'=>'Twilio disabled - logged'];
    }

    $sid = TWILIO_ACCOUNT_SID;
    $token = TWILIO_AUTH_TOKEN;
    $from = TWILIO_WHATSAPP_FROM;

    $url = "https://api.twilio.com/2010-04-01/Accounts/$sid/Messages.json";

    $data = http_build_query([
        'From' => $from,
        'To' => $to_whatsapp_number,
        'Body' => $message_text
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_USERPWD, $sid . ":" . $token);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $errno = curl_errno($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if($errno){
        return ['status'=>'error','error'=>$err];
    }else{
        return json_decode($result, true);
    }
}
