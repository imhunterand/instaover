<?php
require "config.php";
require "class.php";


$url = "https://api.telegram.org/bot$token/setWebhook?url=".current_url()."webhook.php";
file_put_contents("logs/index.php.txt",file_get_contents($url));
//echo $token.  "<br>";
echo "Server Sudah Berjalan :)";
