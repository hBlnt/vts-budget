<?php
$id_user = '';
if (isset($_SESSION['username']) && isset($_SESSION['id_user']) && is_int($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
}

if (!isset($_SESSION['username']) || !isset($_SESSION['id_user']) || !is_int($_SESSION['id_user'])) {
    redirection('index.php?e=0');
}
?>
<div class='container px-4 px-lg-5'>

    <h1 class='font-weight-light my-2 text-center text-decoration-underline'>New tour</h1>
    <br>
    <form method="post" action="form_action.php">
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

        <h2 class="text-center">Which attractions would you like?</h2>

        <div class='row gx-4 gx-lg-5'>
            <?php
            $cities = getCitiesWithAttractions($pdo);

            foreach ($cities as $city) {
                $attractions = getTableData($pdo, 'attractions', 'id_city', $city['id_city'], true);
                echo "   
                 <div class='col-md-4 mb-5'>
                     <div class='card h-100'>
                         <div class='card-body'>    
                             <h2>" . $city['city_name'] . "</h2>
                             ";
                foreach ($attractions as $attraction) {
                    echo "<input type='checkbox' name='attractions[]' value='{$attraction['id_attraction']}'> {$attraction['attraction_name']}<br>";
                }
                echo "
                         </div>
                     </div>
                 </div>
                ";
            }
            ?>
        </div>

        <br>
        <br>
        <div class="text-center">
            <button type='submit' class='btn btn-success border-3 border-dark text-center btn-lg'>Next
                page
            </button>
        </div>
    </form>
</div>
</div>
