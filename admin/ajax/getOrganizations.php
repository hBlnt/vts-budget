<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../../db_config.php';
require_once '../../functions.php';

$sql = "SELECT o.id_organization, o.org_name,o.email,c.city_name, o.is_banned, o.date_time FROM organizations o 
INNER JOIN cities c ON o.id_city = c.id_city

ORDER BY o.org_name";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$organizations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$number = 1;

foreach ($organizations as $organization) {


    if ($organization['is_banned'] == 0)
        $is_banned = 'active';
    else
        $is_banned = 'banned';

    $data[] = [
        $number,
        $organization['org_name'],
        $organization['email'],
        $organization['city_name'],
        $is_banned,
        $organization['date_time'],
        '<i class="bi bi-pencil-fill editOrganization pointer" data-id="' . $organization['id_organization'] . '" title="Edit"></i> 
        &nbsp; <i class="bi bi-trash-fill deleteOrganization pointer" data-id="' . $organization['id_organization'] . '" data-name="' . $organization['org_name'] . '" title="Delete"></i> '
    ];
    $number++;
}

$json_data = [
    "draw" => 1,
    "data" => $data
];

echo json_encode($json_data);