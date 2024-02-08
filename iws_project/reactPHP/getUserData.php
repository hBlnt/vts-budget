<?php
header("Content-type: application/json; charset=UTF-8");

require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $userData = getUserData((int)$_GET['id_user']) ?? 0;
    if ($userData=== false) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    } else {
        http_response_code(200);
        echo json_encode(array("firstname" => $userData['firstname'], "lastname" => $userData['lastname']));
    }

} else {
    http_response_code(405);
    echo json_encode(array("status" => "error", "message" => "Method Not Allowed"));
}
exit();
