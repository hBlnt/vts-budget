<?php

header("Content-type: application/json; charset=UTF-8");

require_once 'config.php';
require_once 'functions.php';
if ($_SERVER["REQUEST_METHOD"] === "PATCH") {
    try {
        $data = json_decode(file_get_contents("php://input"), true);
        $id_user = $data["id_user"];
        $firstname = $data["firstname"];
        $lastname = $data['lastname'];
        if (empty($id_user) || empty($firstname) || empty($lastname)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Empty variables']);
            exit();
        }

        $stmt = $GLOBALS['pdo']->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname WHERE id_user = :id_user");
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);

        $stmt->execute();
        sleep(1);
        echo json_encode(array("status" => "ok", "message" => "Data updated successfully"));
        exit();
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array("status" => "error", "message" => "Database error"));
        exit();
    }
} else {
    http_response_code(405);
    echo json_encode(array("status" => "error", "message" => "Method Not Allowed"));
    exit();
}

