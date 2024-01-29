<?php

require_once '../db_config.php';
require_once '../functions.php';
//require_once 'isAdmin.php';

$name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email = isset($_POST['email']) ? trim(strip_tags($_POST['email'])) : '';
$city = isset($_POST['city']) ? trim(strip_tags($_POST['city'])) : '';
$password = isset($_POST['password']) ? trim(strip_tags($_POST['password'])) : '';
//$email = trim(strip_tags($_POST['email'])) ?? '';
//$city = trim(strip_tags($_POST['city'])) ?? '';
//$password = trim(strip_tags($_POST['password'])) ?? '';
var_dump($_POST);


if (empty($name) or strlen($password) < 8 or empty($email) or empty($city))
    redirection('organizations.php?m=1');
else if (dataExists($pdo, "id_organization", "organizations", ['org_name'], [$name]))
    redirection('organizations.php?m=2');
else {
    insertIntoOrganizations($pdo, $name, $password, $email, $city);
    redirection('organizations.php?m=3');
}