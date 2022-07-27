<?php

date_default_timezone_set('Asia/Jakarta');
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));


if (empty($url['path'])) {
    $server = 'localhost';
    $username = 'root';
    $password = "";
    $db = "reportig"; // server reporting instaports
} else {
    $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

    $server = $url["host"];
    $username = $url["user"];
    $password = $url["pass"];
    $db = substr($url["path"], 1);
}
$conn = new mysqli($server, $username, $password, $db);
if ($conn->connect_error) {
    die("DB Ne Boss");
}
// Ganti Token Client
$conx = $conn->query("SELECT * FROM `bot`");
$hasilx = $conx->fetch_assoc();
$token = $hasilx['token'];

