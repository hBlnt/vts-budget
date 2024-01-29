<?php
require_once '../../db_config.php';
require_once '../../functions.php';

if (isset($_POST['id_organization'])) {
    $id_organization = (int)$_POST['id'];
    deleteTableData($pdo, 'organizations', 'id_organization', $id_organization);
}
if (isset($_POST['id_city'])) {
    $id_city = (int)$_POST['id_city'];
    $city_data = getTableData($pdo, 'cities', 'id_city', $id_city, false);

    $image = "../../" . $city_data['city_image'];


    echo deleteCityImage($image);
    deleteTableData($pdo, 'cities', 'id_city', $id_city);

}

if (isset($_POST['id_attraction'])) {
    $id_attraction = (int)$_POST['id_attraction'];
    $imageNames = array_map(function ($element) {
        return $element['path'];
    }, getImageNames($pdo, $id_attraction));

    foreach ($imageNames as $image) {

        $correctImage = "../../" . $image;
        if (file_exists($correctImage)) {
            if (unlink($correctImage)) {
                echo "Image $correctImage has been deleted successfully.";
            } else {
                echo "Error deleting image $correctImage.";
            }
        } else {
            echo "Image $correctImage does not exist.";
        }
    }
    deleteImages($pdo, $id_attraction);
    //first delete images
    deleteTableData($pdo, 'attractions', 'id_attraction', $id_attraction);

}

if(isset($_POST['id_user_ban']))
{
    $id_user = (int)$_POST['id_user_ban'];
    banUser($pdo,$id_user);
}
if(isset($_POST['id_user_unban']))
{
    $id_user = (int)$_POST['id_user_unban'];
    unbanUser($pdo,$id_user);
}
