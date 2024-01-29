<?php


header('Content-Type: application/json; charset=utf-8');
require_once '../../db_config.php';
require_once '../../functions.php';


$attractions = getAllData($pdo, 'attractions');

$number = 1;

//var_dump($cities);
foreach ($attractions as $attraction) {
    $cityData = getCityData($pdo,$attraction['attraction_name'] );
    $orgData = getOrganization($pdo, $attraction['id_organization']);
    $data[] = [
        $number,
        $attraction['attraction_name'],
        $orgData['org_name'],
        $cityData['city_name'],
        $attraction['address'],
        $attraction['type'],
        $attraction['popularity'],
        '<i class="bi bi-pencil-fill editAttraction pointer" data-id="' . $attraction['id_attraction'] . '" title="Edit"></i> 
        &nbsp; <i class="bi bi-trash-fill deleteAttraction pointer" data-id="' . $attraction['id_attraction'] . '" data-name="' . $attraction['attraction_name'] . '" title="Delete"></i> '
    ];
    $number++;
}


$json_data = [
    "draw" => 1,
    "data" => $data
];

echo json_encode($json_data);
