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
            $imageNames = array_map(function ($element) {
                return $element['path'];
            }, getImageNames($pdo, $id_attraction));
            $_SESSION['names'] = $imageNames;
            // delete images from server
            foreach ($imageNames as $image) {

                if (file_exists($image)) {
                    if (unlink($image)) {
                        echo "Image $image has been deleted successfully.";
                    } else {
                        echo "Error deleting image $image.";
                    }
                } else {
                    echo "Image $image does not exist.";
                }
            }
            deleteImages($pdo, $id_attraction);
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
                    $lastAttractionId = insertAttraction($pdo, $attraction_name, $type, $description, $address, $id_organization);

                    $imageIds = [];
                    foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
                        $file_tmp = $_FILES["images"]["tmp_name"][$key];

                        if (exif_imagetype($file_tmp) !== 2)
                            redirection('new_attraction.php?e=34');

                        if (is_uploaded_file($file_tmp)) {
                            $file_name = time() . "-" . mt_rand(100, 1000) . ".jpg";
                            $upload = "db_images/" . $file_name;

                            if (move_uploaded_file($file_tmp, $upload)) {
                                $lastImageID = insertImage($pdo, $upload);
                                $imageIds[] = $lastImageID;
                            }
                        }
                    }
                    foreach ($imageIds as $id) {
                        insertAttractionImage($pdo, $lastAttractionId, $id);
                    }

                    //concept
//                    $arrayImages =[];
//                    $imageIDS =[];
//                    foreach ($arrayImages as $image)
//                    {
//                       $lastImageID = insertImage($pdo,$image);
//                       array_push($imageIDS,$lastImageID);
//                    }
//                    foreach ($imageIDS as $id)
//                    {
//                       insertAttractionImages($pdo,$lastAttractionId,$id);
//                    }

                    if ($lastAttractionId > 0)
                        redirection('new_attraction.php?e=27');
                    else
                        redirection('new_attraction.php?e=28');
                } else {
                    redirection('new_attraction.php?e=35');

                }
            } catch
            (Exception $e) {
                error_log("****************************************");
                error_log($e->getMessage());
                error_log("file:" . $e->getFile() . " line:" . $e->getLine());
                redirection('new_attraction.php?e=28');
            }

            break;


    }

} else {
    redirection('index.php?e=1');
}
