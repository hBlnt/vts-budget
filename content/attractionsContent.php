<?php
$id_user = '';
$id_organization = '';
if (isset($_SESSION['username']) && isset($_SESSION['id_user']) && is_int($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
}
if (isset($_SESSION['username']) && isset($_SESSION['id_organization']) && is_int($_SESSION['id_organization'])) {
    $id_organization = $_SESSION['id_organization'];
}

?>
<div class="container px-5 justify-content-center">
    <div class="row gx-4 gx-lg-5 text-center justify-content-center my-5">
        <div class="col-lg-5">
            <h1 class="font-weight-light">ATTRACTIONS</h1>
            <div>
                <?php
                if (!empty($id_organization)) {
                    echo "
                
                <a href='new_attraction.php'>
                    <button class='btn btn-success border-3 border-dark text-center btn-lg'><h1>New attraction</h1></button>
                </a>
                ";
                }
                require_once 'error.php';
                ?>
            </div>
        </div>
    </div>
    <div class="col-lg-8 pb-4 mx-auto">

        <form method="post" class="form-control" action="attractions.php">
            <label for="search_by_name">Search by name</label>
            <input type="text" name="search" id="search_by_name" class="form-control"
                   placeholder="Search"><br>
            <?php
            if (empty($_GET['city']) and !empty($id_user)) {

                ?>
                <label for="country">Country:</label>
                <select name="country" id="country" class="form-select">
                    <option value="">Choose</option>
                    <?php

                    $cities = getCountries($pdo);
                    foreach ($cities

                    as $city){
                    $country = $city;

                    ?>
                    <option value="<?php echo $country; ?>"><?php echo $country;
                        }
                        ?></option>
                </select>
                <?php
            }
            ?>
            <br>
            <fieldset class="border p-1 rounded-3   ">
                <legend>Choose your attraction types</legend>
                <?php
                $sqlTypes = 'SELECT DISTINCT a.type FROM attractions a 
                INNER JOIN cities c ON a.id_city = c.id_city ';

                if (isset($_GET['city'])) {
                    $city = trim($_GET['city']);
                    $sqlTypes .= " WHERE c.`city_name` = :city";
                }
                if (!empty($id_organization)) {
                    $sqlTypes .= " WHERE id_organization = :id_organization";
                }

                $stmtTypes = $pdo->prepare($sqlTypes);

                if (isset($_GET['city'])) {
                    $stmtTypes->bindParam(':city', $city, PDO::PARAM_STR);
                }
                if (!empty($id_organization)) {
                    $stmtTypes->bindParam(':id_organization', $id_organization, PDO::PARAM_STR);
                }
                $stmtTypes->execute();

                $types = $stmtTypes->fetchAll(PDO::FETCH_ASSOC);
                $dynamicCount = count($types);
                $divider = round($dynamicCount / 2);
                sort($types);

                echo "<div class='row gx-4 gx-lg-5 px-2'>";
                $typeCounter = 1;
                echo "<div class='col-sm-6 col-md-6 col-lg-4 py-4'>";
                foreach ($types as $type) {
                    $typeSingle = $type['type'];
                    if ($typeCounter % $divider === 0)
                        echo "<input type='checkbox' id='type{$typeCounter}' name='typeArray[]' value='$typeSingle'> <label for='type{$typeCounter}'>{$typeSingle}</label><br></div><div class='col-sm-6 col-md-6 col-lg-4 py-4'>";
                    else
                        echo "<input type='checkbox' id='type{$typeCounter}' name='typeArray[]' value='$typeSingle'> <label for='type{$typeCounter}'>{$typeSingle}</label><br>";

                    $typeCounter++;
                }


                echo "</div></div>";
                ?>
            </fieldset>
            <?php
            if (!empty($id_organization)) {
                echo "
<br>
                <label for='order'>What order would you like?</label>
                <select name='order' id='order' class='form-select'>
                    <option value='' selected>Choose an order</option>
                    <option value='alphabet_asc'>Alphabetical (A-Z)</option>
                    <option value='alphabet_desc'>Backwards alphabetical (Z-A)</option>
                    <option value='popularity_high'>Popularity (Highest first)</option>
                    <option value='popularity_low'>Popularity (Lowest first)</option>
                </select>
                ";
            }
            ?>
            <br>
            <div class="text-center">
                <button type='submit' id='searchButton' class='btn btn-primary border-3 border-dark w-100'>
                    Search
                </button>
            </div>

        </form>
    </div>
    <div class="row gx-4 gx-lg-5">
        <?php
        //        echo $_SESSION['id_user'];
        $city = '';
        $selectedType = $_POST['typeArray'] ?? [];
        $selectedCountry = $_POST['country'] ?? '';
        $insertedSearch = $_POST['search'] ?? '';
        $order = $_POST['order'] ?? '';

        $sqlAttractionSelect = "SELECT a.id_attraction,a.id_organization,a.attraction_name,a.type, a.popularity, a.id_city FROM `attractions` a";

        $sqlAttractionSelect .= " INNER JOIN `cities` c ON a.`id_city` = c.`id_city`";

        $sqlAttractionSelect .= " INNER JOIN organizations o ON a.id_organization =  o.id_organization ";

        $dynamicWhereClause = "";
        if (isset($_GET['city'])) {
            $city = trim($_GET['city']);
            $dynamicWhereClause .= " WHERE c.`city_name` = '$city'";
        }

        $dynamicWhereClause .= empty($dynamicWhereClause) ? " WHERE " : " AND ";
        $dynamicWhereClause .= "o.is_banned = 0";

        if (!empty($selectedCountry)) {
            $dynamicWhereClause .= empty($dynamicWhereClause) ? " WHERE " : " AND ";
            $dynamicWhereClause .= "c.country = :country";
        }
        if (!empty($selectedType)) {
            $typeList = "'" . implode("','", $selectedType) . "'";
            $dynamicWhereClause .= empty($dynamicWhereClause) ? " WHERE " : " AND ";
            $dynamicWhereClause .= "type IN ($typeList)";
        }
        if (!empty($insertedSearch)) {
            $dynamicWhereClause .= empty($dynamicWhereClause) ? " WHERE " : " AND ";
            $dynamicWhereClause .= " (a.attraction_name LIKE :search)";
        }

        if (!empty($id_organization)) {

            $orgData = getTableData($pdo, 'organizations', 'id_organization', $id_organization, false);
            $orgCity = $orgData['id_city'];
            $dynamicWhereClause .= empty($dynamicWhereClause) ? " WHERE " : " AND ";
            $dynamicWhereClause .= "a.id_city = :org_city";
            if (!empty($order)) {
                switch ($order) {

                    case "alphabet_asc":
                        $dynamicWhereClause .= " ORDER BY a.attraction_name ASC";
                        break;
                    case "alphabet_desc":
                        $dynamicWhereClause .= " ORDER BY a.attraction_name DESC";
                        break;
                    case "popularity_high":
                        $dynamicWhereClause .= " ORDER BY a.popularity DESC, a.attraction_name ASC";
                        break;
                    case "popularity_low":
                        $dynamicWhereClause .= " ORDER BY a.popularity ASC, a.attraction_name ASC";
                        break;
                }
            }
        } else
            $dynamicWhereClause .= " ORDER BY a.attraction_name ASC";

        $sqlAttractionSelect .= $dynamicWhereClause;


        $stmtAttractionSelect = $pdo->prepare($sqlAttractionSelect);

        if (!empty($selectedCountry)) {
            $stmtAttractionSelect->bindParam(':country', $selectedCountry, PDO::PARAM_STR);
        }
        if (!empty($insertedSearch)) {
            $searchTerm = '%' . $insertedSearch . '%';
            $stmtAttractionSelect->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        }
        if (!empty($id_organization)) {
            $stmtAttractionSelect->bindParam(':org_city', $orgCity, PDO::PARAM_STR);
        }
        $stmtAttractionSelect->execute();
        if ($stmtAttractionSelect->rowCount() > 0) {
            while ($row = $stmtAttractionSelect->fetch(PDO::FETCH_ASSOC)) {
                $attractionName = $row["attraction_name"];
                $attractionId = $row["id_attraction"];
                $attractionType = $row["type"];
                $attractionPopularity = $row["popularity"];
                $cityData = getCityData($pdo, $attractionName);
                $path = getAttractionImagePath($pdo, $attractionId);
                $img_path = '';
                echo "   
        <div class=' col-md-6 col-lg-4 mb-5'>
            <div class='card h-100 text-center border-5 border-light'style='background-image: url(" . $path . "); background-size: cover; background-position: center '>
                <div class='card-body  d-flex flex-column'>    
                    <h2 class='title'>{$attractionName}</h2>
                    
                    
                    
                    ";
                if (empty($_SESSION['username']))
                    echo "
                    <div class='py-5'></div>";

                //Not sure about this if
                if (!empty($id_user)) {
                    echo "

                    <div class='mt-auto title'>
                        <p>City: {$cityData["city_name"]} </p>
                        <p>Type: {$attractionType} </p>
                    </div>
                    <div class='mt-auto'>
                        <form method='post' action='attraction_details.php'>
                            <input type='hidden' name='attraction_id' value='{$attractionId}'>
                            <input type='submit' class='btn btn-light border-3 border-dark' value='More information'>
                        </form>
                    </div>
                    ";
                }
                if (!empty($id_organization)) {
                    echo "
                   <i class='text-decoration-underline mt-auto title'>Popularity: {$attractionPopularity} </i> 
                   <div class='d-flex justify-content-between mt-auto'>
                        <form method='post' action='edit_attraction.php'>
                            <input type='hidden' name='action' value='editAttraction'>
                            <input type='hidden' name='id_attraction' value='{$attractionId}'>
                            <input type='submit' class='btn btn-warning border-3 border-dark' value='Edit'>
                        </form>
                        <form method='post' action='form_action.php' class='mt-auto'>
                            <input type='hidden' name='action' value='deleteAttraction'>
                            <input type='hidden' name='id_attraction' value='{$attractionId}'>
                            <input type='submit' class='btn btn-danger border-3 border-dark' value='Delete'>
                        </form>
                    </div>
                    ";

                }
                echo "
                </div>
            </div>
        </div>
    ";
            }

        } else {
            echo "<h2 class='text-center pb-5'>There are no attractions like this.</h2>";
        }
        ?>
    </div>
</div>

