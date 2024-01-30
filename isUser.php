<?php
if(!isset($_SESSION['username']) || !isset($_SESSION['id_user']) || !is_int($_SESSION['id_user'])){
    redirection('index.php?e=0');

}
?>
