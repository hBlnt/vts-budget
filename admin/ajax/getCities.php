<?php

header('Content-Type: application/json; charset=utf-8');
require_once '../../db_config.php';
require_once '../../functions.php';


$cities = getAllData($pdo, 'cities');

$number = 1;

//var_dump($cities);
foreach ($cities as $city) {
    $data[] = [
        $number,
        $city['city_name'],
        $city['country'],
        '<i class="bi bi-pencil-fill editCity pointer" data-id="' . $city['id_city'] . '" title="Edit"></i> 
        &nbsp; <i class="bi bi-trash-fill deleteCity pointer" data-id="' . $city['id_city'] . '" data-name="' . $city['city_name'] . '" title="Delete"></i> '
    ];
    $number++;
}


$json_data = [
    "draw" => 1,
    "data" => $data
];

echo json_encode($json_data);
