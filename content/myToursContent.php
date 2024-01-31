<?php
$id_user = '';
if (isset($_SESSION['username']) && isset($_SESSION['id_user']) && is_int($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
}

?>
<div class='container px-4 px-lg-5'>

    <h1 class='font-weight-light my-2 text-center text-decoration-underline'>My tours</h1>
    <br>
    <?php
    $isTourMade = false;

    $getUserTours = getTableData($pdo, 'tours', 'id_user', $id_user, true);

    if (!empty($getUserTours))
        $isTourMade = true;

    if ($isTourMade) {
        echo "<table class='table table-bordered text-center table-sm'>";
        echo "<tr>
<th></th>
            <th>Tour name</th>
            <th>Date</th>
            <th colspan='2'>Operation</th>
        </tr>";
        $rowCounter = 0;
        foreach ($getUserTours as $tour) {
            $id_tour = $tour['id_tour'];
            $rowCounter++;
            $dateTime = date('Y.m.d', strtotime($tour['datetime']));
            echo "<tr>";
            echo "<td>{$rowCounter}</td>";
            echo "<td>{$tour['tour_name']}</td>";
            echo "<td>{$dateTime}</td>";

            echo "<td><form method='POST' action='getTour.php'>";
            echo "<input type='hidden' name='id_tour' value='" . $id_tour . "'>";
            echo "<button class='btn btn-primary' type='submit'>View</button>";
            echo "</form></td>";

            echo "<td><form method='POST' action='form_action.php'>";
            echo "<input type='hidden' name='id_tour' value='" . $id_tour . "'>";
            echo "<input type='hidden' name='action' value='userDeleteTour'>";
            echo "<button class='btn btn-danger' type='submit'>Delete</button>";

            echo "</form></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else
        echo "<h2 class='text-center pb-5'>You didn't make any tours yet.</h2>";
    ?>
    <div class="text-center">
        <?php
        require_once 'error.php';
        ?>

        <a href="new_tour.php">
            <button class='btn btn-success border-3 border-dark text-center btn-lg'><h1>New tour</h1></button>
        </a>
    </div>
</div>
