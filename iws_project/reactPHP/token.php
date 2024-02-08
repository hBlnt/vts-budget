<?php

header("Content-type: application/json; charset=UTF-8");
require_once 'config.php';
require_once 'functions.php';

$token = createToken(30);


echo json_encode($token);
