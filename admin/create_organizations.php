<?php

require_once '../db_config.php';
require_once '../functions.php';

$name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
$email = isset($_POST['email']) ? trim(strip_tags($_POST['email'])) : '';
$city = isset($_POST['city']) ? trim(strip_tags($_POST['city'])) : '';
$password = isset($_POST['password']) ? trim(strip_tags($_POST['password'])) : '';


if (empty($name) or strlen($password) < 8 or empty($email) or empty($city))
    redirection('organizations.php?m=1');
else if (dataExists($pdo, "id_organization", "organizations", ['org_name'], [$name]))
    redirection('organizations.php?m=2');
else if (dataExists($pdo, 'id_organization', 'organizations', ['email'], [$email]))
    redirection('organizations.php?m=2');
else if (!str_contains($email, $orgDomain))
    redirection('organizations.php?m=4');
else {
    insertIntoOrganizations($pdo, $name, $password, $email, $city);
    redirection('organizations.php?m=3');
}