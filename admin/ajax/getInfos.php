<?php

header('Content-Type: application/json; charset=utf-8');
require_once '../../db_config.php';
require_once '../../functions.php';


$infos = getIPInfo($pdo);

$number = 1;

$isVPN = 'didn\'t use VPN';
foreach ($infos as $info) {

    if($info['proxy'] == 1)
        $isVPN = 'used VPN';
    $data[] = [
        $number,
        $info['email'],
        $info['user_agent'],
        $info['ip_address'],
        $info['device_type'],
        $info['country'],
        $isVPN,
        $info['date_time']
    ];
    $number++;
}


$json_data = [
    "draw" => 1,
    "data" => $data
];

echo json_encode($json_data);
