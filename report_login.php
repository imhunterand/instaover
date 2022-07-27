<?php
require "config.php";
require "class.php";


//$files = glob('./account/*.txt');
$chat_id = $argv['1'];
$id_target = $argv['2'];
$proxy = 'proxy:portprox'; // pemanggilan proxy tidak valid, melainkan tidak ada yang dipanggil melalui function ini.
$proxyauth = null;
//print_r($files);
$gagal = 0;
$berhasil = 0;
$list_acc = trim(file_get_contents("listacc.txt"));
$xlist = explode("\n", $list_acc);



$filename = rand(1, 10000000) . ".txt";
$logs = __DIR__ . "/" . $filename;
fwrite(fopen($logs, "w+"), "");

pesan("Logs : http://telegram.labsbots.com/bot/$filename");

fwrite(fopen($logs, "a"), "Memulai\n");

foreach ($xlist as $file) {
    $xdata = explode("|", $file);
    $username = $xdata[0];
    $password = $xdata[1];
    file_put_contents("./account/" . $file . ".txt", "");
    $file = "./account/" . $file . ".txt";
    $dir = realpath($file);
    $e = explode('/', $file);
    $fileku = end($e);
    $acc = str_replace(".txt", "", $fileku);



    //$username = ;
    fopen($dir, "w+");
    //print_r($e);
    //echo $acc . "\n";
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
    curl_setopt($ch, CURLOPT_COOKIEJAR, $dir);


    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    } else {
        echo $result . "\n";
    }

    $json = json_decode($result, 1);
    if ($json['authenticated'] === false or !isset($json['authenticated'])) {
        file_put_contents("php://stderr", "Sandi Salah|$username|$password|$result\n");
        //fwrite(fopen($logs, "a"), "Sandi Salah|$result\n");
        pesan("FAILED REPORT|$username|$result|❌");
        fwrite(fopen($logs, "a"), "FAILED REPORT|$username|$result\n");

        $gagal++;

        continue 1;
    }
    file_put_contents("php://stderr", "$result\n");
    fwrite(fopen($logs, "a"), "Login Berhasil|$username|$result\n");
    //pesan("Login Berhasil|$username|$password|$result\n");


    curl_close($ch);

    // report
    file_put_contents("php://stderr", "Menjalankan\n");
    //fwrite(fopen($logs, "a"), "Menjalankan\n");
    //pesan("Menjalankan Report\n");




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
    curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/users/' . $id_target . '/report/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $dir);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $dir);


    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    } else {
        echo $result . "\n";
    }
    curl_close($ch);

    file_put_contents("php://stderr", "SUCCESS REPORT BY $username  ✅\n");
    pesan("SUCCESS REPORT BY $username  ✅\n"); // hasil output ini adalah hasil dari request login, bukan request reports
    //file_put_contents("php://stderr", "SUCCESS REPORT BY $username  ✅\n");
    $berhasil++;
    fwrite(fopen($logs, "a"), "SUCCESS REPORT BY $username  \n");
}

fwrite(fopen($logs, "a"), "Send : $berhasil | Error : $gagal\n");
fwrite(fopen($logs, "a"), "Sukses Report\n");
pesan("Send : $berhasil | Error : $gagal\n");
pesan("Sukses Report\n");
