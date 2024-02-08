<?php
header("Content-type: application/json; charset=UTF-8");

require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === "GET") {

    $id_attraction = (int)$_GET['id_attraction'] ?? 0;
    $singleAttraction = getAttractionByID($id_attraction);
    $id_user = (int)$_GET['id_user'] ?? 0;

    if ($singleAttraction === false) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Wrong attraction']);
        exit();
    }
    $isCityFavourite = isFavouriteAttractionExist($singleAttraction['id_city'], $id_user);
    $singleAttraction['isCityFavourite'] = $isCityFavourite;

    if (empty($_GET['id_attraction']) || empty($_GET['id_user'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Not all parameters included']);
        exit();
    }
    if (!empty($singleAttraction)) {
        http_response_code(200);
        echo json_encode($singleAttraction);
    }

} else {
    http_response_code(405);
    echo json_encode(array("status" => "error", "message" => "Method Not Allowed"));
}
exit();

