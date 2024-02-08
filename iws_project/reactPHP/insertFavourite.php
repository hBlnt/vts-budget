<?php
header("Content-type: application/json; charset=UTF-8");

require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    try {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!empty($data)) {
            if (isset($data['id_attraction']))
                $id_attraction = (int)$data['id_attraction'];

            if (isset($data['id_user']))
                $id_user = (int)$data['id_user'];

            if (isset($id_user) and isset($id_attraction))
                $simpleFavourite = insertFavouriteAttraction($id_user, $id_attraction);
        }


        if (empty($id_attraction) || empty($id_user)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Not enough variables given']);
            exit();
        }
        if (empty($simpleFavourite)) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Not found']);
        } else {
            http_response_code(200);
            echo json_encode(array("status" => "success", "message" => "Successful insert"));
        }
        exit();

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array("status" => "error", "message" => "Database error!"));
        exit();
    }
} else {
    http_response_code(405);
    echo json_encode(array("status" => "error", "message" => "Method Not Allowed"));
    exit();
}

