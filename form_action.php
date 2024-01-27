<?php
session_start();
require_once "db_config.php";
require_once "functions.php";

$id_user = '';
if (isset($_SESSION['username']) && isset($_SESSION['id_user']) && is_int($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
}

$referer = $_SERVER['HTTP_REFERER'];
$action = $_POST["action"];

if ($action != "" and in_array($action, $formActions) and strpos($referer, SITE) !== false) {
    switch ($action) {
        case "userDeleteTour":
            $id_tour = trim($_POST['id_tour'] ?? '');
            deleteTableData($pdo, 'tours', 'id_tour', $id_tour);
            redirection("my_tours.php");
            break;
        case "userDeleteFavourite":
            $id_attraction = trim($_POST['id_attraction'] ?? '');
            deleteFavouriteAttraction($pdo, $id_user, $id_attraction);
            redirection("favourites.php");
            break;
        case "newTour":
            $tour_name = trim($_POST['tour_name'] ?? '');
            $tour_type = trim($_POST['tour_type'] ?? '');
            $attractions = $_POST['attractions'] ?? [];
            if (empty ($tour_type) || empty($tour_name) || empty($attractions))
                redirection('index.php?e=0;');


            $lastTourId = insertNewTour($pdo, $id_user, $tour_name, $tour_type);
            insertTourAttractions($pdo, $lastTourId, $attractions);
            redirection('new_tour.php?success');
            break;
        case "makeFavourite":

            $id_attraction = trim($_POST['id_attraction'] ?? '');
            insertFavouriteAttraction($pdo, $id_user, $id_attraction);
            redirection("favourites.php");
            break;

    }

} else {
    redirection('index.php?e=1');
}
