<?php
require_once __DIR__ . '/../lib/send_whatsapp.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $to = $_POST['to'];
    $msg = $_POST['message'];
    $res = send_whatsapp_message($to, $msg);
    echo '<pre>';
    print_r($res);
    echo '</pre>';
    echo '<p><a href="index.php">Back</a></p>';
}
