<?php
session_start();
require_once "db_config.php";
require_once "functions.php";

$referer = $_SERVER['HTTP_REFERER'];
$action = $_POST["action"];

$id_user = '';
if (isset($_SESSION['username']) && isset($_SESSION['id_user']) && is_int($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
}
$id_organization = '';
if (isset($_SESSION['username']) && isset($_SESSION['id_organization']) && is_int($_SESSION['id_organization'])) {
    $id_organization = $_SESSION['id_organization'];
}


if ($action != "" and in_array($action, $formActions) and strpos($referer, SITE) !== false) {
    switch ($action) {
        case "userDeleteTour":
            $id_tour = trim($_POST['id_tour'] ?? '');
            deleteTableData($pdo, 'tours', 'id_tour', $id_tour);
            redirection("my_tours.php?e=32");
            break;
        case "userDeleteFavourite":
            $id_favourite = trim($_POST['favourite'] ?? '');
            $id_attraction = trim($_POST['attraction'] ?? '');
            if (deleteFavouriteAttraction($pdo, $id_user, $id_favourite, $id_attraction))
                redirection("favourites.php?e=32");
            else
                redirection("favourites.php?e=28");

            break;
        case "userEditFavourite":
            $id_favourite = trim($_POST['favourite'] ?? '');
            $id_attraction = trim($_POST['attraction'] ?? '');
            $new_favourite = trim($_POST['select'] ?? '');
            if (!isSameCity($pdo, $new_favourite, $id_favourite))
                redirection("favourites.php?e=17");
            if (updateFavouriteAttraction($pdo, $id_user, $id_favourite, $id_attraction, $new_favourite))
                redirection("favourites.php?e=31");
            else
                redirection("favourites.php?e=28");

            break;
        case "deleteAttraction":
            $id_attraction = trim($_POST['id_attraction'] ?? '');
            $imageNames = getImageNames($pdo, $id_attraction);
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
            $tour_name = htmlspecialchars(trim($_POST['tour_name']) ?? '');
            $tour_type = htmlspecialchars(trim($_POST['tour_type']) ?? '');
            $attractions = $_POST['attractions'] ?? [];
            if (empty ($tour_type) || empty($tour_name) || empty($attractions))
                redirection('my_tours.php?e=4');


            $lastTourId = insertNewTour($pdo, $id_user, $tour_name, $tour_type);
            insertTourAttractions($pdo, $lastTourId, $attractions);
            redirection('my_tours.php?e=27');
            break;
        case "makeFavourite":

            $id_attraction = trim($_POST['id_attraction'] ?? '');
            insertFavouriteAttraction($pdo, $id_user, $id_attraction);
            redirection("favourites.php");
            break;

        case "newAttraction":

            $attraction_name = htmlspecialchars(trim($_POST['attraction_name']) ?? '');
            $type = htmlspecialchars(trim($_POST['type']) ?? '');
            $description = htmlspecialchars(trim($_POST['description']) ?? '');
            $address = htmlspecialchars(trim($_POST['address']) ?? '');
            if (empty ($attraction_name) || empty($type) || empty($description) || empty($address))
                redirection('new_attraction.php?e=4');

            try {
                if (!existsAttraction($pdo, $attraction_name)) {
                    $lastAttractionId = insertAttraction($pdo, $attraction_name, $type, $description, $address, $id_organization);

                    $usersWithNews = getAllData($pdo, 'users');
                    foreach ($usersWithNews as $user) {
                        if ($user['news']) {
                            $id_user = $user['id_user'];
                            $email = $user['email'];
                            try {
                                $body = " <h1 style='color: #327d81'>Toorizm</h1><br>
                                          <h2>New attraction</h2><br>
                                         <p>There is a new attraction on Toorizm, called <strong style='color: #21cfd5'>" . $attraction_name . "</strong> check it out if you would like.</p><br>
                                         <a href=" . SITE . "email.php?newid={$lastAttractionId}>Check attraction</a>
                                          ";
                                sendEmail($pdo, $email, $emailMessages['news'], $body, $id_user);
                            } catch (Exception $e) {
                                error_log("****************************************");
                                error_log($e->getMessage());
                                error_log("file:" . $e->getFile() . " line:" . $e->getLine());
                            }
                        }
                    }

                    $imageIds = [];
                    foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
                        $file_tmp = $_FILES["images"]["tmp_name"][$key];

                        if (exif_imagetype($file_tmp) !== 2)
                            redirection('new_attraction.php?e=34');

                        if (is_uploaded_file($file_tmp)) {
                            $file_name = uniqid('img-', true) . "-" . mt_rand(100, 1000) . ".jpg";
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

        case "newComment":

            $id_user = $_POST['id_user'] ?? '';
            $id_attraction = $_POST['id_attraction'] ?? '';
            $comment = htmlspecialchars(trim($_POST['comment'])) ?? '';


            if (empty($id_user) || empty($id_attraction) || empty($comment))
                redirection('attractions.php?e=4');

            $_SESSION['current_page'] = $id_attraction;

            $filteredCommentData = getFilteredCommentData($comment);
            $lastId = insertComment($pdo, $id_user, $id_attraction, $comment, $filteredCommentData);

            if ($lastId) {
                foreach ($filteredCommentData['words'] as $key => $value) {
                    if ($value !== 0)
                        insertIntoBadWords($pdo, $lastId, $key, $value);
                }
                redirection('new_comment.php');
            } else
                redirection('attractions.php?e=28');

            break;
        case "editAttraction":
            $id_attraction = $_POST['id_attraction'] ?? '';
            $attraction_name = htmlspecialchars(trim($_POST['attraction_name'])) ?? '';
            $description = htmlspecialchars(trim($_POST['description'])) ?? '';
            $address = htmlspecialchars(trim($_POST['address'])) ?? '';
            $type = htmlspecialchars(trim($_POST['type'])) ?? '';
            var_dump($_POST);
            if (empty ($attraction_name) || empty($id_attraction) || empty($description) || empty($address) || empty($type))
                redirection('attractions.php?e=4');

            try {
                $result = updateAttraction($pdo, $id_attraction, $attraction_name, $description, $address, $type);
                if ($result) {
                    redirection('attractions.php?e=31');
                } else
                    redirection('attractions.php?e=28');

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
