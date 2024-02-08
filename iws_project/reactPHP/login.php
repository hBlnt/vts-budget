<?php

header("Content-type: application/json; charset=UTF-8");

require_once 'config.php';
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $loginData = null;
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!empty($data)) {
            if (isset($data['email']))
                $email = (string)$data['email'];

            if (isset($data['password']))
                $password = (string)$data['password'];

            if (isset($email) and isset($password))
                $loginData = checkUserLogin($email, $password);
        }
        if (empty($data) || !isset($email) || !isset($password)) {
            http_response_code(400);
            echo json_encode(array("status" => "error", "message" => "Not enough parameters"));
            exit();
        }
        if ($loginData) {
            http_response_code(200);
            echo json_encode(array("status" => "success", "message" => "You have logged in!", "email" => $email, "id_user" => $loginData['id_user']));
        } else {
            http_response_code(404);
            echo json_encode(array("status" => "error", "message" => "Not found user"));
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
