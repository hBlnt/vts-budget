<?php
require_once '../db_config.php';
require_once '../functions.php';
//require_once 'isAdmin.php';

$name = trim(strip_tags($_POST['name'])) ?? '';
$country = trim(strip_tags($_POST['country'])) ?? '';
$image = $_FILES['image'];

var_dump($_POST);

$newFileName = "";

if (empty($name) or empty($country))
    redirection('new_city.php?m=1');
else if (dataExists($pdo,'id_city', 'cities', ['city_name'], [$name]))
    redirection('new_city.php?m=2');
else if (!empty($image['name']) and !imageReady($image)) {
    redirection('new_city.php?m=3');
} else {
    if (!empty($image['name'])) {
        $directory = "../db_images/city_images/";


        $extension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        $newFileName = uniqid('city_image-', true) . '-' . str_replace(' ', '', $name) . '-' . mt_rand(10, 100) . '.' . $extension;

        if (!move_uploaded_file($image['tmp_name'], $directory . $newFileName))
            redirection('new_city.php?m=4');

    }
    $id_city = insertCity($pdo, $name, $country, $newFileName);


    redirection('new_city.php?m=6');

}