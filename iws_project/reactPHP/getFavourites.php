<?php
header("Content-type: application/json; charset=UTF-8");

require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === "GET") {

    $favourites = getFavouriteAttractions($_GET['id_user']);
    if (empty($_GET['id_user'])) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
        exit();
    }
    if (empty($favourites)) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Not found']);
    } else {
        http_response_code(200);
        echo json_encode($favourites);
    }

} else {
    http_response_code(405);
    echo json_encode(array("status" => "error", "message" => "Method Not Allowed"));
}
exit();

