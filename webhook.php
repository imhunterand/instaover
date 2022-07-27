<?php
require "config.php";
require "class.php";

use React\EventLoop\Factory;
use unreal4u\TelegramAPI\HttpClientRequestHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use unreal4u\TelegramAPI\Telegram\Methods\EditMessageText;
use unreal4u\TelegramAPI\Telegram\Methods\SendMessage;
use unreal4u\TelegramAPI\Telegram\Types\Message;
use unreal4u\TelegramAPI\TgLog;
use unreal4u\TelegramAPI\Telegram\Types\Inline\Keyboard\Markup;

$loop = Factory::create();
$tgLog = new TgLog($token, new HttpClientRequestHandler($loop));



$data = file_get_contents("php://input");
// Debug
file_put_contents("h.txt", $data);



$json = json_decode($data, 1);

$pesans = $json['message'];
$client = $chat_id = $pesans['from']['id'];
$pesan = $pesans['text'];
$message_id = $pesans['message_id'];
$tanggal = date("Y-m-d");
$name = $pesans['chat']['first_name'];


if ($pesan == "/id") {
    pesan($chat_id);
    die;
}

if (preg_match("/\/REPORT/", $pesan)) {
    $file = realpath("run.php");
    $pisah = explode(" ", $pesan);
    $username = $pisah[1];
    $password = $pisah[2];
    $target = $pisah[3];
    $berapakali = $pisah[4];
    $rand = rand(1, 1000000);
    exec("php -f '$file' '$username' '$password' '$target' '$berapakali' '$chat_id' '$rand' > /dev/null &");
    die;
}

if (preg_match("/\/add/", $pesan)) {
    $pisah = explode(" ", $pesan);
    if (!isset($pisah[1]) || !isset($pisah[2])) {
        pesan("UNTUK MENAMBAHKAN IG BARU
    <code>/add username password</code>");
        die;
    }
    $username = $pisah[1];
    $password = $pisah[2];
    $result = $conn->query("SELECT * FROM `list_username` WHERE `ig` = '$username'");
    if ($result->num_rows != 0) {
        pesan("IG SUDAH ADA DI LIST DATABASE !!!");
        die;
    }


    $proxy = "zproxy.lum-superproxy.io:22225";
    $proxyauth = 'lum-customer-hl_6f5a8ba9-zone-data_center:Azizah666';

    pesan("Login Proses");
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
    //curl_setopt($ch, CURLOPT_PROXY, $proxy);
    //curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    $cookie = rand(1, 100000) . rand(1, 100000) . ".txt";
    fwrite(fopen($cookie, "w+"), "");
    $real = realpath($cookie);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $real);


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
        pesan("Koreksi lagi Sandi / username anda !!!");

        //fwrite(fopen($logs, "a"), "Sandi Salah\n");
        die;
    }
    curl_close($ch);
    file_put_contents("php://stderr", "$result\n");
    //fwrite(fopen($logs, "a"), "Login Berhasil|$username|$password|$result\n");
    pesan("Login Berhasil|$username|$password|Berhasil Ditambahkan\n");
    pesan("Silakan ketik /list untuk melihat daftar ig");
    $cookiedata = file_get_contents($real);
    file_put_contents("f", "$real\n");
    $data = file_get_contents("$real");
    file_put_contents("x", "$data\n");
    //$conn->query("INSERT INTO `list_username` (`id`, `id_telegram`, `ig`, `cache`) VALUES (NULL, '$chat_id', '$username', '$data');");
    die;
}



if ($pesan == "/list") {
    $batas = 10;

    $halaman = 1;

    $halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;
    $previous = $halaman - 1;
    $next = $halaman + 1;

    $result = $conn->query("SELECT * FROM `list_username` WHERE `id_telegram` = '$chat_id';");
    $jumlah_data = $result->num_rows;



    $total_halaman = ceil($jumlah_data / $batas);
    // Kirim Menu 
    $SendMessage = new SendMessage();
    $SendMessage->message_id = $message_id;
    $SendMessage->chat_id = $chat_id;
    $SendMessage->text = "*LIST IG $halaman/$total_halaman*";
    $row = null;

    $kons = [];
    $result = $conn->query("SELECT * FROM `list_username` WHERE `id_telegram` = '$chat_id' limit $halaman_awal, $batas;");
    while ($row = $result->fetch_assoc()) {
        $user =
            [];
        //$username = $row['username'];
        $name = $row['ig'];
        $user[] = ['text' => $name, 'callback_data' => 'selectid_' . $row['id'] . ''];
        $kons[] = $user;
    }
    $btn =
        [];
    if ($halaman > 1) {
        $btn[] = ['text' => '<- previous ', 'callback_data' => 'list_member_on_page_' . $previous];
    }
    if ($halaman < $total_halaman) {
        $btn[] = ['text' => 'Next ->', 'callback_data' => 'list_member_on_page_' . $next];
    }
    $kons[] = $btn;
    $inlineKeyboard = new Markup([
        'inline_keyboard' => $kons
    ]);


    $SendMessage->disable_web_page_preview = true;
    $SendMessage->parse_mode = 'Markdown';
    $SendMessage->reply_markup = $inlineKeyboard;
    $promise = $tgLog->performApiRequest($SendMessage);
    $loop->run();
    die;
}



if (isset($json['callback_query'])) {
    $callback = $json['callback_query'];
    $from = $callback['from'];
    $msg = $callback['message'];
    // Clicked data
    $clicked = $callback['data'];
    $message_id = $msg['message_id'];
    $chat_id = $from['id'];
    $name = $from['first_name'];
    if ($clicked == "list_member_on" or preg_match("/list_member_on_page_/", $clicked)) {
        $batas = 10;
        if (preg_match("/list_member_on_page_/", $clicked)) {
            $pisah = explode("page_", $clicked);
            $halaman = $pisah['1'];
        } else {
            $halaman = 1;
        }
        $halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;
        $previous = $halaman - 1;
        $next = $halaman + 1;

        $result = $conn->query("SELECT * FROM `list_username` WHERE `id_telegram` = '$chat_id';");
        $jumlah_data = $result->num_rows;
        $total_halaman = ceil($jumlah_data / $batas);
        // Kirim Menu 
        $editMessageText = new EditMessageText();
        $editMessageText->message_id = $message_id;
        $editMessageText->chat_id = $chat_id;
        $editMessageText->text = "*LIST IG $halaman/$total_halaman*";
        $row = null;

        $kons = [];
        $result = $conn->query("SELECT * FROM `list_username` WHERE `id_telegram` = '$chat_id' limit $halaman_awal, $batas;");
        while ($row = $result->fetch_assoc()) {
            $user =
                [];
            $name = $row['ig'];
            $user[] = ['text' => '@' . $name, 'callback_data' => 'selectid_' . $row['id'] . ''];
            $kons[] = $user;
        }
        $btn =
            [];
        if ($halaman > 1) {
            $btn[] = ['text' => '<- previous ', 'callback_data' => 'list_member_on_page_' . $previous];
        }
        if ($halaman < $total_halaman) {
            $btn[] = ['text' => 'Next ->', 'callback_data' => 'list_member_on_page_' . $next];
        }
        $kons[] = $btn;
        $inlineKeyboard = new Markup([
            'inline_keyboard' => $kons
        ]);


        $editMessageText->disable_web_page_preview = true;
        $editMessageText->parse_mode = 'Markdown';
        $editMessageText->reply_markup = $inlineKeyboard;
        $promise = $tgLog->performApiRequest($editMessageText);
        $loop->run();
    }
    if (preg_match("/selectid_/", $clicked)) {
        $pisah = explode("_", $clicked);
        $id = $pisah[1];


        $conn->query("UPDATE `username` SET `on_ig` = '$id' WHERE `id_telegram` = $chat_id;");
        $conx = $conn->query("SELECT * FROM `list_username` WHERE `id` = $id");
        $hasilx = $conx->fetch_assoc();
        $ig = $hasilx['ig'];

        $sendMessage = new SendMessage();
        $sendMessage->chat_id = $chat_id;
        $sendMessage->text = "SELECTED ACCOUNT ❗️❗️❗️
`$ig`";
        $sendMessage->parse_mode = 'Markdown';
        $promise = $tgLog->performApiRequest($sendMessage);
        $loop->run();
    }
    die;
}

if ($pesan == "/start") {

    $result = $conn->query("SELECT * FROM `username` WHERE `id_telegram` = '$chat_id'");
    if ($result->num_rows == 0) {
        $conn->query("INSERT INTO `username` (`id`, `id_telegram`, `on_ig`) VALUES (NULL, '$chat_id', '');");
    }
    $inlineKeyboard = new Markup([
        'inline_keyboard' => [
            [
                ['text' => 'lihat daftar akun', 'callback_data' => 'list_member_on']
            ]
        ]
    ]);
    $sendMessage = new SendMessage();
    $sendMessage->chat_id = $chat_id;
    $sendMessage->text = 'SELAMAT MENGGUNAKAN BOT
    
TEKAN TOMBOL LIST UNTUK MELIHAT LIST AKUN INSTAGRAM REPORTER';
    $sendMessage->reply_markup = $inlineKeyboard;
    $sendMessage->parse_mode = 'Markdown';

    $messageCorrectionPromise = $tgLog->performApiRequest($sendMessage);

    $loop->run();
    die;
}


// Report

pesan("[Automatic reports to Instagram]

Coded By : ANDRI /.ft RIDWAN
________________________________________");
$target = $pesan;
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
curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . '/cookie.txt');
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
    //fwrite(fopen($logs, "a"), "php://stderr", "ID Tidak ditemukan\n");
    pesan("ID Tidak ditemukan\n");

    die("");
}


$id = $json["graphql"]["user"]["id"];
echo "id :$id";
pesan("Mengambil id Target : $id |Username : $target\n");

//pesan("REPORTER ACCOUNT : $id_og");
$file_report = realpath("report_login.php");
exec("php $file_report $chat_id $id > /dev/null &");


pesan("Menjalankan Report\n");
die;
pesan("Sukses Report\n");
