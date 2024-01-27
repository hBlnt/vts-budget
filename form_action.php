<?php
session_start();
require_once "db_config.php";
require_once "functions.php";

$id_user = '';
if (isset($_SESSION['username']) && isset($_SESSION['id_user']) && is_int($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
}
$id_organization = '';
if (isset($_SESSION['username']) && isset($_SESSION['id_organization']) && is_int($_SESSION['id_organization'])) {
    $id_organization = $_SESSION['id_organization'];
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
        case "deleteAttraction":
            $id_attraction = trim($_POST['id_attraction'] ?? '');
            deleteTableData($pdo, 'attractions', 'id_attraction', $id_attraction);
            redirection("attractions.php?e=32");
            break;
        case "newTour":
            $tour_name = trim($_POST['tour_name'] ?? '');
            $tour_type = trim($_POST['tour_type'] ?? '');
            $attractions = $_POST['attractions'] ?? [];
            if (empty ($tour_type) || empty($tour_name) || empty($attractions))
                redirection('index.php?e=4;');


            $lastTourId = insertNewTour($pdo, $id_user, $tour_name, $tour_type);
            insertTourAttractions($pdo, $lastTourId, $attractions);
            redirection('new_tour.php?success');
            break;
        case "makeFavourite":

            $id_attraction = trim($_POST['id_attraction'] ?? '');
            insertFavouriteAttraction($pdo, $id_user, $id_attraction);
            redirection("favourites.php");
            break;

        case "newAttraction":

            $attraction_name = trim($_POST['attraction_name'] ?? '');
            $type = trim($_POST['type'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $address = trim($_POST['address'] ?? '');
            if (empty ($attraction_name) || empty($type) || empty($description) || empty($address))
                redirection('new_attraction.php?e=4');

            try {
                if (!existsAttraction($pdo, $attraction_name)) {
                    $result = insertAttraction($pdo, $attraction_name, $type, $description, $address, $id_organization);

                    //ide megy a :
                        /*1. insert into images
                        $arrayIDimages = [];
                        $lastAttractionid =''
                        lastinsertid attraction
                        2. array_push lastinsertid $arrayIDimages;
                        3.
                        insert into attractions_images(pdo,$id_attraction
                     */
                    if ($result > 0)
                        redirection('new_attraction.php?e=27');
                    else
                        redirection('new_attraction.php?e=28');
                } else {
                    redirection('new_attraction.php?e=35');

                }
            } catch (Exception $e) {
                error_log("****************************************");
                error_log($e->getMessage());
                error_log("file:" . $e->getFile() . " line:" . $e->getLine());
                redirection('exercises.php?e=28');
            }

            break;


    }

} else {
    redirection('index.php?e=1');
}
