<?php
$id_user = '';

if (isset($_SESSION['username']) && isset($_SESSION['id_user']) && is_int($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
}
$getFavourites = getFavouriteAttractions($pdo, $id_user);
$isFavouritesExist = false;
if (!empty($getFavourites))
    $isFavouritesExist = true;
?>

<div class='container px-4 px-lg-5'>

    <h1 class='font-weight-light my-2 text-center text-decoration-underline'>Favourite attractions</h1>
    <br>
    <?php
    if ($isFavouritesExist) {
        echo "";
        echo "<table class='table table-bordered text-center table-striped'>";
        echo "<tr>
            <th></th>
            <th>Attraction name</th>
            <th>City name</th>
            <th colspan='2'>Operation</th>
        </tr>";
//        var_dump($getFavourites);
        $rowCounter = 0;
        foreach ($getFavourites as $favourite) {
            $rowCounter++;
            $id_attraction = $favourite['id_attraction'];
            $attractions = getAttractionsByCity($pdo, $favourite['id_city']);
            echo "<tr>";
            echo "<td>{$rowCounter}</td>";
            echo "<td> <form method='post' action='form_action.php'>";
            echo "<select name='select' id='attraction_name' class='form-select'>";
            foreach ($attractions as $attraction) {
                if ($favourite['id_attraction'] == $attraction['id_attraction'])
                    echo '<option selected value="' . $attraction['id_attraction'] . '">' . $attraction['attraction_name'] . '</option>';
                else
                    echo '<option value="' . $attraction['id_attraction'] . '">' . $attraction['attraction_name'] . '</option>';
            }
            ?>
            </select>
            <input type='hidden' name='favourite' value='<?= $favourite['id_favourite'] ?>'>
            <input type='hidden' name='attraction' value='<?= $favourite['id_attraction'] ?>'></td>

            <?php

            echo "<td>{$favourite['city_name']}</td>";
            echo "<td><button class='border-1 btn btn-warning' name='action' value='userEditFavourite' type='submit'>Edit</button></td>";
            echo "<td><button class='btn btn-danger' name='action' value='userDeleteFavourite' type='submit'>Delete</button></form></td>";

            echo "</tr>";
        }
        echo "</table>";
    } else
        echo "<h2 class='text-center'>You don't have any favourite attractions yet.</h2>";

    echo "<div class='text-center text-info text-decoration-underline'>";

    require_once 'error.php';
    echo "</div>";
    ?>

</div>
