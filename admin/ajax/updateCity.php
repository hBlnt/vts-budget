<?php
require_once '../../db_config.php';
require_once '../../functions.php';
$cityName = isset($_POST["name"]) ? sanitize($_POST["name"]) : null;
$country = isset($_POST["country"]) ? sanitize($_POST["country"]) : null;

$image = $_FILES['file'] ?? [];
$newFileName = '';
$id_city = isset($_POST["id_city"]) ? (int)sanitize($_POST["id_city"]) : null;

$op = isset($_POST["op"]) ? sanitize($_POST["op"]) : null;

$cityData = getTableData($pdo, 'cities', 'id_city', $id_city, false);

$currentImage = "";
if (!empty($cityData['city_image']))
    $currentImage = "../../" . $cityData['city_image'];

$aclass = 'alert alert-warning';
$statusResponse = 'error';
$message = 'Errora!';

if (!empty($cityName) and !empty($country) and !empty($id_city) and $op === 'update' and is_ajax()) {

    if (!empty($image)) {
        if (!empty($image['name']) and !imageReady($image))
            redirection('all_cities.php?m=1');
        else {
            if (!empty($image['name'])) {
                if (!empty($currentImage))
                    deleteCityImage($currentImage);
                $directory = "../../db_images/city_images/";
                $extension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
                $newFileName = uniqid('city_image-', true) . '-' . str_replace(' ', '', $cityName) . mt_rand(10, 100) . '.' . $extension;
                if (!move_uploaded_file($image['tmp_name'], $directory . $newFileName)) {
                    redirection('all_cities.php?m=2');
                }
            }
        }
    }
    $aclass = 'alert alert-success';
    $statusResponse = 'success';
    $newFileNameWithPath = '';
    if (!empty($newFileName))
        $newFileNameWithPath = 'db_images/city_images/' . $newFileName;
    if (updateCity($pdo, $cityName, $id_city, $country, $newFileNameWithPath)) {
        $message = "City updated successfully!";
    } else {
        $message = "There were no changes";
    }
}

$data = array('status' => $statusResponse, 'message' => $message, 'aclass' => $aclass);
header('Content-Type:application/json;charset=utf-8');
echo json_encode($data);
