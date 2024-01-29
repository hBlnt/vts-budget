<?php
require_once '../../db_config.php';
require_once '../../functions.php';

$orgName = isset($_POST["name"]) ? sanitize($_POST["name"]) : null;
$email = isset($_POST["email"]) ? sanitize($_POST["email"]) : null;

if (empty($email) or !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = "Not email format";
}

$blockedDomain = "org.com";
list($name,$domain) = explode("@",$email);
if($domain === $blockedDomain)
    $message = "Organization domain must be used";

$id_organization = isset($_POST["id_organization"]) ? (int)sanitize($_POST["id_organization"]) : null;
$password = isset($_POST["password"]) ? sanitize($_POST["password"]) : null;
$status = isset($_POST["status"]) ? sanitize($_POST["status"]) : null;

$id_city = isset($_POST["city"]) ? sanitize($_POST["city"]) : null;
$op = isset($_POST["op"]) ? sanitize($_POST["op"]) : null;

$aclass = 'alert alert-warning';
$statusResponse = 'error';
$message = 'Errora!';

if(!empty($orgName) and !empty($id_organization) and !empty($email) and $op === 'update' and is_ajax()) {
    $aclass = 'alert alert-success';
    $statusResponse = 'success';

    if (updateOrganization($pdo, $orgName, $id_organization,$email,$id_city, $status, $password)) {
        $message = "Organization updated successfully!";
    } else {
        $message = "There were no changes";
    }
}

$data = array('status' => $statusResponse,'message'=>$message,'aclass'=>$aclass);
header('Content-Type:application/json;charset=utf-8');
echo json_encode($data);