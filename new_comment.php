<?php
session_start();

$current_page = $_SESSION['current_page'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>new commentredirection</title>
</head>
<body onload="document.form1.submit()">


<form method='post' action='attraction_details.php' name="form1">
    <input type='hidden' name='attraction_id' value='<?=$current_page?>'>
</form>


</body>
</html>
