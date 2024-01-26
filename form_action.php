<?php
session_start();
require_once "db_config.php";
require_once "functions.php";

$referer = $_SERVER['HTTP_REFERER'];
$action = $_POST["action"];

if ($action != "" and in_array($action, $formActions) and strpos($referer, SITE) !== false) {
    switch ($action) {
        case "userDeleteTour":
            $id_tour = $_POST['id_tour'] ?? '';
            deleteTableData($pdo, 'tours', 'id_tour', $id_tour);
            redirection("my_tours.php");
    }

} else {
    redirection('index.php?e=1');
}
