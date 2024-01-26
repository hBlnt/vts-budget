<?php
$id_user = '';
if (isset($_SESSION['username']) && isset($_SESSION['id_user']) && is_int($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
}

if(!isset($_SESSION['username']) || !isset($_SESSION['id_user']) || !is_int($_SESSION['id_user'])){
    redirection('index.php?e=0');
}
?>
<div class='container px-4 px-lg-5'>

    <h1 class='font-weight-light my-2 text-center text-decoration-underline'>New tour</h1>
    <br>
        <form method="post" action="new_tour2.php">
            <label for="tour_name">What should the name of the tour be?</label>
            <input type="text" name="tour_name" id="tour_name" class="form-control"
                   placeholder="Your tour name . . ."><br>
            <label for="tour_type">What type of transport is your tour preferred ? </label>
            <?php
            echo '<select name="tour_type" id="tour_type" class="form-control">';
            $sql = "SHOW COLUMNS FROM tours LIKE 'tour_type'";
            $query = $pdo->prepare($sql);
            $query->execute();
            $tourTypes = $query->fetch(PDO::FETCH_ASSOC);
            $enum_values = explode("','", substr($tourTypes['Type'], 6, -2));

            foreach ($enum_values as $value) {
                echo '<option value="' . $value . '">' . $value . '</option>';
            }
            ?>
            </select><br>

            <label for="attraction_count">How many attractions would you like?</label>
            <input type="number" name="attraction_count" min=2 max="15" id="attraction_count" class="form-control">
            <br>
            <br>
            <div class="text-center">
                <button type='submit' id='searchButton' class='btn btn-success border-3 border-dark text-center btn-lg'>Next page</button>
            </div>
        </form>
</div>
</div>
