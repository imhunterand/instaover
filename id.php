<?php

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/username_target/?__a=1');
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

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}else
{
    //echo $result;
}
curl_close($ch);
$json = json_decode($result,1);
$id = $json["graphql"]["user"]["id"];
echo "id :$id";
