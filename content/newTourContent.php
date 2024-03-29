<?php
$id_user = '';
if (isset($_SESSION['username']) && isset($_SESSION['id_user']) && is_int($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
}

//if (!isset($_SESSION['username']) || !isset($_SESSION['id_user']) || !is_int($_SESSION['id_user'])) {
//    redirection('index.php?e=0');
//}
?>
<div class='container px-4 px-lg-5'>

    <h1 class='font-weight-light my-2 text-center text-decoration-underline'>New tour</h1>
    <br>
    <form method="post" action="form_action.php">
        <input type='hidden' name='action' value='newTour'>
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
        sort($enum_values);

        foreach ($enum_values as $value) {
            echo '<option value="' . $value . '">' . $value . '</option>';
        }
        ?>
        </select><br>

        <h2 class="text-center">Which attractions would you like?</h2>
        <br><br>

        <div class='row gx-4 gx-lg-5'>
            <?php
            $cities = getCitiesWithAttractions($pdo);


            foreach ($cities as $city) {
                $attractions = getTableData($pdo, 'attractions', 'id_city', $city['id_city'], true);
                $city_name = $city['city_name'];
                echo "   
                 <div class='col-xs-12 col-sm-6 col-md-6 col-lg-4 mb-5'>
                     <div class='card '>
                        <div class='accordion ' id='accordionExample{$city_name}'>
                            <div class='accordion-item'>
                                <h2 class='accordion-header bg-light' id='heading{$city_name}'>
                                    <button class='accordion-button collapsed fs-3 bg-light border-light' type='button' data-bs-toggle='collapse' data-bs-target='#collapse{$city_name}' aria-expanded='false' aria-controls='collapse{$city_name}'>
                                          {$city_name}
                                    </button> 
                                </h2>
                            <div id='collapse{$city_name}' class='accordion-collapse collapse' aria-labelledby='heading{$city_name}' data-bs-parent='#accordionExample{$city_name}'>
                                <div class='accordion-body'>
                             
                             ";
                $counter = 0;
                foreach ($attractions as $attraction) {
                    $identitifaction = $city_name . $counter;
                    echo "
                    <div class='d-inline-flex'><input type='checkbox' name='attractions[]' id='attraction{$identitifaction}' value='{$attraction['id_attraction']}'> <label class='ms-2' for='attraction{$identitifaction}'>{$attraction['attraction_name']}</label></div><br>";
                    $counter++;
                }
                echo "
</div></div></div></div> <!-- Accordion closers-->

                     </div>
                 </div>
                ";
            }
            ?>
        </div>

        <br>
        <br>
        <div class="text-center">
            <button type='submit' class='btn btn-success border-3 border-dark text-center btn-lg'>Make tour</button>
        </div>
    </form>
</div>
</div>
