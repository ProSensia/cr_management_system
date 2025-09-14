<?php
// =============================
// index.php (Main Entry Point)
// =============================

// ---- Database Connection ----
define('DB_HOST', 'premium281.web-hosting.com');   // Namecheap MySQL host
define('DB_NAME', 'prosdfwo_cr_pak_austria');      // DB name
define('DB_USER', 'prosdfwo_crpak_austria');       // DB user
define('DB_PASS', 'CR.Pak-austria@2025');          // DB password

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    die("❌ Database Connection Failed: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");

// ---- WhatsApp Setup (Twilio) ----
// Sign up free at https://www.twilio.com/try-twilio and enable WhatsApp sandbox
// require __DIR__ . '/vendor/autoload.php';  // after running: composer require twilio/sdk
// use Twilio\Rest\Client;

// define('TWILIO_SID', 'YOUR_TWILIO_SID');
// define('TWILIO_TOKEN', 'YOUR_TWILIO_AUTH_TOKEN');
// define('TWILIO_WHATSAPP_FROM', 'whatsapp:+1415XXXXXXX'); // Twilio sandbox number
// define('MY_WHATSAPP_NUMBER', 'whatsapp:+923107717890'); // Your number or student’s

// function send_whatsapp($to, $message) {
//     $client = new Client(TWILIO_SID, TWILIO_TOKEN);
//     try {
//         $client->messages->create(
//             $to,
//             [
//                 'from' => TWILIO_WHATSAPP_FROM,
//                 'body' => $message
//             ]
//         );
//         return true;
//     } catch (Exception $e) {
//         return "❌ WhatsApp error: " . $e->getMessage();
//     }
// }

// ---- Simple Router ----
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$routes = [
    'home'      => 'pages/home.php',
    'dashboard' => 'pages/dashboard.php',
    'about'     => 'pages/about.php',
    'login'     => 'pages/login.php',
    'sendmsg'   => 'pages/sendmsg.php', // new page to send WA message
];

if (array_key_exists($page, $routes)) {
    include $routes[$page];
} else {
    include "pages/404.php";
}

$mysqli->close();


