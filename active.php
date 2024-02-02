<?php
require_once "db_config.php";
require_once "functions.php";
session_start();
if (isset($_GET['token'])) {
    $token = trim($_GET['token']);
}

if (!empty($token) and strlen($token) === 40) {

        $sql = "UPDATE users SET active = 1, registration_token = '',registration_token_expiry = NULL
            WHERE binary registration_token = :token AND registration_token_expiry > now()";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();


    if ($stmt->rowCount() > 0) {
        redirection('register_authentication.php?e=6');
    } else {
        redirection('register_authentication.php?e=12');
    }
} else {
    redirection('register_authentication.php?e=0');
}
session_destroy();
