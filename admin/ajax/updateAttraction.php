<?php
require_once '../../db_config.php';
require_once '../../functions.php';

$attraction_name = isset($_POST["name"]) ? sanitize($_POST["name"]) : null;
$address = isset($_POST["address"]) ? sanitize($_POST["address"]) : null;
$description = isset($_POST["description"]) ? sanitize($_POST["description"]) : null;
$type = isset($_POST["type"]) ? sanitize($_POST["type"]) : null;

$id_city = isset($_POST["city"]) ? (int)sanitize($_POST["city"]) : null;
$id_attraction = isset($_POST["id_attraction"]) ? (int)sanitize($_POST["id_attraction"]) : null;
$id_organization = isset($_POST["organization"]) ? (int)sanitize($_POST["organization"]) : null;

$op = isset($_POST["op"]) ? sanitize($_POST["op"]) : null;

$aclass = 'alert alert-warning';
$statusResponse = 'error';
$message = 'Error!';

if(!empty($attraction_name) and !empty($address) and !empty($type) and !empty($description) and !empty($id_attraction) and !empty($id_city) and !empty($id_organization) and $op === 'update' and is_ajax()) {
    $aclass = 'alert alert-success';
    $statusResponse = 'success';

    if (updateAttractionAllFields($pdo,$id_attraction,$id_organization,$id_city,$attraction_name,$type,$address,$description)) {
        $message = "Attraction updated successfully!";
    } else {
        $message = "There were no changes";
    }
}

$data = array('status' => $statusResponse,'message'=>$message,'aclass'=>$aclass);
header('Content-Type:application/json;charset=utf-8');
echo json_encode($data);
