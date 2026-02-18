<?php
// composer require guzzlehttp/guzzle vlucas/phpdotenv
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use Dotenv\Dotenv;

// 1) Load .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$TENANT_ID     = $_ENV['TENANT_ID']     ?? '';
$CLIENT_ID     = $_ENV['CLIENT_ID']     ?? '';
$CLIENT_SECRET = $_ENV['CLIENT_SECRET'] ?? '';
$SENDER_UPN    = $_ENV['SENDER_UPN']    ?? '';         // pengirim
$TO            = $_ENV['TO_ADDRESS']    ?? $SENDER_UPN; // penerima uji

if (!$TENANT_ID || !$CLIENT_ID || !$CLIENT_SECRET || !$SENDER_UPN) {
    fwrite(STDERR, "Missing one or more env vars: TENANT_ID, CLIENT_ID, CLIENT_SECRET, SENDER_UPN\n");
    exit(1);
}

$authority = "https://login.microsoftonline.com/{$TENANT_ID}/oauth2/v2.0/token";
$scope     = "https://graph.microsoft.com/.default";

// server database
$servername = "10.1.12.199";
$username = "root";
$password = "Super@!6871"; // Enter MySQL root password if set
$dbname = "dblicense2";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Run a query

$sql1 = "SELECT status,DATE_FORMAT(date_update_status,'%d-%M-%Y') AS tgl_update,no_tiket,remark, pic, item, description, qty,"; 
$sql2 = "DATE_FORMAT(start_date,'%d-%M-%Y') AS tgl_mulai, DATE_FORMAT(end_date,'%d-%M-%Y') AS tgl_selesai,";
$sql3 = "DATE_FORMAT(renewal_date,'%d-%M-%Y') AS tgl_renewal , vendor, mail_pic, email_admin, hp_pic, hp_admin FROM products"; 
$sql4 = " WHERE DATE_ADD(date_update_status, INTERVAL 1 DAY)=DATE_FORMAT(NOW(),'%Y-%m-%d')";

$sql = $sql1 . $sql2 . $sql3 . $sql4 ; 
$result = $conn->query($sql);

// Output results
$emailBody = "";
$emailpic="";
$hppic="";
if ($result->num_rows > 0) {

    echo "Update Status Perpanjangan License"." \n" ;
    while($row = $result->fetch_assoc()) {
        $emailBody = "";
        $emailBody .= "Dear Bapak/Ibu/Dokter ".$row['pic'].", \n \n";
        $emailBody .= "Bersama dengan email ini, kami informasikan status perpanjangan license yang bapak/ibu/dokter ajukan sbb : "." \n \n";   
        $emailBody .= "License : ".$row['item']." \n";
        $emailBody .= "Description : ".$row['description']." \n";
        $emailBody .= "Qty : ".$row['qty']." \n";       
        $emailBody .= "Start Date License : ".$row['tgl_mulai'] ." \n";
        $emailBody .= "End Date License : ".$row['tgl_selesai']." \n";        
        $emailBody .= "Renewal Date : ".$row['tgl_renewal']." \n"; 
        $emailBody .= "Vendor : ".$row['vendor']." \n \n";
        $emailBody .= "Status : ".$row['status']." \n";
        $emailBody .= "Update Status Date : ".$row['tgl_update']." \n";
        $emailBody .= "No Tiket : ".$row['no_tiket']." \n";
        $emailBody .= "Remark : ".$row['remark']." \n"; 
        $emailBody .= " \n"." \n";
        $emailBody .= "Regards,"." \n";
        $emailBody .= " \n"." \n";
        $emailBody .= "IT Asset"." \n";        
        $emailpic="";
        $emailadmin="";
        $emailpic .=$row['mail_pic'];
        $emailadmin .=$row['email_admin'];
        $hppic .=$row['hp_pic'].",".$row['hp_admin'] ;

        // mail($emailpic, "Reminder ke-1 Perpanjangan License", $emailBody);
try {
    $http = new Client(['timeout' => 20]);

    // 2) Get Access Token (Client Credentials)
    $tokenResp = $http->post($authority, [
        'form_params' => [
            'client_id'     => $CLIENT_ID,
            'client_secret' => $CLIENT_SECRET,
            'grant_type'    => 'client_credentials',
            'scope'         => $scope,
        ],
        'headers' => [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ],
    ]);

    $tokenBody = json_decode($tokenResp->getBody()->getContents(), true);
    if (!isset($tokenBody['access_token'])) {
        throw new RuntimeException('Token error: ' . json_encode($tokenBody));
    }
    $accessToken = $tokenBody['access_token'];

  // 3) Call Graph /sendMail
    $url = "https://graph.microsoft.com/v1.0/users/{$SENDER_UPN}/sendMail";

    $payload = [
        "message" => [
            "subject" => "Update Status Perpanjangan License",
            "body" => [
                "content" =>  $emailBody
            ],
            "from" => [
                "emailAddress" => ["address" => $SENDER_UPN]
            ],
            "toRecipients" => [
                ["emailAddress" => ["address" => $emailpic]]
            ],
             "ccRecipients" => [
                ["emailAddress" => ["address" => "itasset.shho@siloamhospitals.com"]]
            ]
        ],
        "saveToSentItems" => true
    ];

   $sendResp = $http->post($url, [
        'headers' => [
            'Authorization' => "Bearer {$accessToken}",
            'Content-Type'  => 'application/json'
        ],
        'body' => json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    ]);

    // /sendMail returns 202 Accepted on success with empty body
    $status = $sendResp->getStatusCode();
    $text   = $sendResp->getBody()->getContents();
    // mulai email ke-2
       $payload = [
        "message" => [
            "subject" => "Update Status Perpanjangan License",
            "body" => [
                "content" =>  $emailBody
            ],
            "from" => [
                "emailAddress" => ["address" => $SENDER_UPN]
            ],
            "toRecipients" => [
                ["emailAddress" => ["address" => $emailadmin]]
            ]
        ],
        "saveToSentItems" => true
    ];
// $sendResp = $http->post($url, [
//     'headers' => [
//          'Authorization' => "Bearer {$accessToken}",
//            'Content-Type'  => 'application/json'
//        ],
//        'body' => json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
//    ]);

    // /sendMail returns 202 Accepted on success with empty body
    $status = $sendResp->getStatusCode();
    $text   = $sendResp->getBody()->getContents();
// selesai email ke-2
        


    echo $status . " " . ($text !== '' ? $text : "OK") . PHP_EOL;

} catch (\Throwable $e) {
    fwrite(STDERR, "Error: " . $e->getMessage() . PHP_EOL);
    if (isset($tokenBody)) {
        fwrite(STDERR, "Last token body: " . json_encode($tokenBody) . PHP_EOL);
    }
    exit(1);
}

        echo "Pic :  " . $row["pic"] . " - Item: " . $row["item"] ." \n" ;

    }
} else {
    echo "0 results";
}

$conn->close();
?>



