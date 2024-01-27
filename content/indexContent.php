<?php
$username = $_SESSION['username'] ?? '';
echo"Hello ".$username;
require_once 'error.php';

