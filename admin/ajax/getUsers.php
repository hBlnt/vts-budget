<?php

header('Content-Type: application/json; charset=utf-8');
require_once '../../db_config.php';
require_once '../../functions.php';


$users = getAllData($pdo, 'users');

$number = 1;

$is_banned = 0;
$button = '';
//var_dump($cities);
foreach ($users as $user) {

    if ($user['is_banned'] == 1) {
        $is_banned = 'banned';
        $button =
            ' <button class="bi btn btn-success unbanUser pointer text-center" data-id="' . $user['id_user'] . '" data-name="' . $user['email'] . '" title="Status" style="width: 75px">Unban</button> ';
    }
    else
    {
        $is_banned = 'active';
        $button =
            ' <button class="bi btn btn-danger banUser pointer text-center" data-id="' . $user['id_user'] . '" data-name="' . $user['email'] . '" title="Status" style="width: 75px">Ban</button> ';
    }

        $data[] = [
            $number,
            $user['email'],
            $user['firstname'],
            $user['lastname'],
            $is_banned,
            $button
        ];
    $number++;
}


$json_data = [
    "draw" => 1,
    "data" => $data
];

echo json_encode($json_data);
