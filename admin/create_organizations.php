<?php

require_once '../db_config.php';
require_once '../functions.php';

$name = trim(strip_tags($_POST['name'])) ?? '';
$email = trim(strip_tags($_POST['email'])) ?? '';
$city = trim(strip_tags($_POST['city'])) ?? '';
$password = trim(strip_tags($_POST['password'])) ?? '';


if (empty($name) or strlen($password) < 8 or empty($email) or empty($city))
    redirection('organizations.php?m=1');
else if (dataExists($pdo,"id_organization", "organizations", ['org_name'], [$name]))
    redirection('organizations.php?m=2');
else {
    insertIntoOrganizations($pdo, $name, $password, $email, $city);
    redirection('organizations.php?m=3');
}