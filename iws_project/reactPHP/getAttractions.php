<?php
header("Content-type: application/json; charset=UTF-8");

require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $fields = ['id_attraction', 'attraction_name', 'type'];

    $attractions = getAttractions($fields);
    if (empty($attractions)) {
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'No attractions exist yet!']);
    } else {
        http_response_code(200);
        echo json_encode($attractions);
    }

} else {
    http_response_code(405);
    echo json_encode(array("status" => "error", "message" => "Method Not Allowed"));
}
exit();
