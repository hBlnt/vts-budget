<?php
$id_user = '';
$id_organization = '';
if (isset($_SESSION['username']) && isset($_SESSION['id_user']) && is_int($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
}
if (isset($_SESSION['username']) && isset($_SESSION['id_organization']) && is_int($_SESSION['id_organization'])) {
    $id_organization = $_SESSION['id_organization'];
//    var_dump($id_organization);
}
?>
<div class="container px-4 px-lg-5">
    <div class="row gx-4 gx-lg-5 text-center justify-content-center my-5">
        <div class="col-lg-5">
            <h1 class="font-weight-light">ATTRACTIONS</h1>
            <?php
            if (!empty($id_organization)) {
                echo "
                
            <div>
                <a href='new_attraction.php'>
                    <button class='btn btn-success border-3 border-dark text-center btn-lg'><h1>New attraction</h1></button>
                </a>
                ";
                require_once 'error.php';
                echo "
            </div>
                ";
            }
            ?>
        </div>
    </div>
    <div class="col-lg-12 pb-4">

        <form method="post" class="form-control">
            <label for="search_by_name">Search</label>
            <input type="text" name="search" id="search_by_name" class="form-control"
                   placeholder="Search"><br>
            <?php
            if (!empty($id_user)) {
                ?>
                <label for="country">Country:</label>
                <select name="country" id="country" class="form-select">
                    <option value="">Choose</option>
                    <?php

                    $sql = 'SELECT DISTINCT country FROM cities';
                    $query = $pdo->prepare($sql);
                    $query->execute();
                    $cities = $query->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($cities

                    as $city){
                    $country = $city['country'];

                    ?>
                    <option value="<?php echo $country; ?>"><?php echo $country;
                        }
                        ?></option>
                </select>
                <?php
            }
            ?>
            <br>
            <fieldset class="border p-1">
                <legend>Choose your attraction types</legend>
                <?php
                $types = getAttractionTypes($pdo);
                foreach ($types as $type) {
                    $typeSingle = $type['type'];
                    echo "<input type='checkbox' name='typeArray[]' value='$typeSingle'> $typeSingle<br>";
                }
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
                <button type='submit' id='searchButton' class='btn btn-light border-3 border-dark accordion-button'>
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

        $dynamicWhereClause = "";
        if (isset($_GET['city'])) {
            $city = trim($_GET['city']);
            $dynamicWhereClause .= " WHERE c.`city_name` = '$city'";
        }

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
                        $dynamicWhereClause .= " ORDER BY a.popularity DESC";
                        break;
                    case "popularity_low":
                        $dynamicWhereClause .= " ORDER BY a.popularity ASC";
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
                $pathData = getAttractionImagePath($pdo, $attractionId);
                $img_path = '';
                if (!empty($pathData["path"]))
                    $img_path = $pathData["path"];
                echo "   
        <div class='col-md-4 mb-5'>
            <div class='card h-100 text-center border-5 border-light'style='background-image: url(" . $img_path . "); background-size: cover;'>
                <div class='card-body title d-flex flex-column'>    
                    <h2>{$attractionName}</h2>
                    
                    
                    ";
                if (!empty($id_user)) {
                    echo "

                    <p>City: {$cityData["city_name"]} </p>
                    <p>Type: {$attractionType} </p>
                    <form method='post' action='attraction_details.php'>
                        <input type='hidden' name='attraction_id' value='{$attractionId}'>
                        <input type='submit' class='btn btn-light border-3 border-dark' value='More information'>
                    </form>";
                }
                if (!empty($id_organization)) {
                    echo "
                   <p class='text-decoration-underline mt-auto'>Popularity: {$attractionPopularity} </p> 
                   <div class='d-flex justify-content-between mt-auto'>
                        <form method='post' action='form_action.php'>
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
            echo "There are no attractions like this.";
        }
        ?>
    </div>
</div>

