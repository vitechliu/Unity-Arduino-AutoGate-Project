<?php

error_reporting(E_ALL);
$url = "http://vitechliu.com/arduino/historyStatus.php";
$post_data = array ("token" => "123456");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
// post数据
curl_setopt($ch, CURLOPT_POST, 1);
// post的变量
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$output = curl_exec($ch);
curl_close($ch);
print_r($output);
?>