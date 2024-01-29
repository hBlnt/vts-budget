<?php

session_start();
require_once "../db_config.php";
require_once "../functions.php";

$password = "";
$passwordConfirm = "";
$action = "";

$referer = $_SERVER['HTTP_REFERER'];


$action = $_POST["action"];

if ($action != "" and in_array($action, $actions) and strpos($referer, SITE) !== false) {


    switch ($action) {
        case "login":

            $username = trim($_POST["username"]);
            $password = trim($_POST["password"]);

            if (!empty($username) and !empty($password)) {
                $data = checkAdminLogin($pdo, $username, $password);
                if ($data and is_int($data['id_admin'])) {
                    $_SESSION['username'] = $data['username'];
                    $_SESSION['id_admin'] = $data['id_admin'];
                    redirection('index.php');
                }  else {
                    redirection('login.php?m=1');
                }

            } else {
                redirection('login.php?m=1');
            }
            break;


        default:
            redirection('login.php?m=1');
            break;


    }

} else {
    redirection('login.php?m=1');
}
