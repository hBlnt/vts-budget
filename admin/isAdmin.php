<?php
if(!isset($_SESSION['username']) || !isset($_SESSION['id_admin']) || !is_int($_SESSION['id_admin'])){
    redirection('login.php?m=0');

}
?>
