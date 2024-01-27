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
    $rowCounter = 0;
    if ($isFavouritesExist) {
        echo "<table class='table table-bordered text-center table-striped'>";
        echo "<tr>
            <th></th>
            <th>Attraction name</th>
            <th>City name</th>
            <th>Delete</th>
        </tr>";
        foreach ($getFavourites as $favourite) {
            $id_attraction = $favourite['id_attraction'];
            $rowCounter++;
            echo "<tr>";
            echo "<td>{$rowCounter}</td>";
            echo "<td>{$favourite['attraction_name']}</td>";
            echo "<td>{$favourite['city_name']}</td>";


            echo "<td><form method='POST' action='form_action.php'>";
            echo "<input type='hidden' name='id_attraction' value='" . $id_attraction . "'>";
            echo "<input type='hidden' name='action' value='userDeleteFavourite'>";
            echo "<button class='btn btn-danger' type='submit'>Delete</button>";

            echo "</form></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else
        echo "<h2>You don't have any favourite attractions yet.</h2>";
    ?>

</div>
