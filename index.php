<?php

require 'vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function getIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    if ($ip == '::1' || $ip == '127.0.0.1') {
        $ip = $_ENV['IP_TEST'];
    }

    return $ip;
}

function getLoc($ip) {
    $access_key = $_ENV['ACCESS_KEY'];
    $url = "http://api.ipstack.com/{$ip}?access_key={$access_key}";
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    
    $response = curl_exec($ch);
    
    curl_close($ch);
    
    $data = json_decode($response, true);
    
    return $data;
}

$user_ip = getIP();
$location_data = getLoc($user_ip);

var_dump($location_data);