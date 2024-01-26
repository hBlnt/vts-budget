<?php
$id_user = '';
if (isset($_SESSION['username']) && isset($_SESSION['id_user']) && is_int($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
}
?>
<div class="container px-4 px-lg-5">
    <div class="row gx-4 gx-lg-5 align-items-center my-5">
        <div class="col-lg-5">
            <h1 class="font-weight-light">ATTRACTIONS</h1>

        </div>
    </div>
    <div class="col-lg-12 pb-4">

        <form method="post" class="form-control">
            <label for="search_by_name">Search</label>
            <input type="text" name="search" id="search_by_name" class="form-control"
                   placeholder="Search"><br>
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
            </select><br>
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
            <br>
            <div class="text-center">
                <button type='submit' id='searchButton' class='btn btn-primary justify-content-center align-content-center'>Search</button>
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
        $sqlAttractionSelect = "SELECT a.id_attraction,a.id_organization,a.attraction_name,a.type FROM `attractions` a";

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
        $sqlAttractionSelect .= $dynamicWhereClause;


        $pdo = connectDatabase($dsn, $pdoOptions);
        $stmtAttractionSelect = $pdo->prepare($sqlAttractionSelect);

        if (!empty($selectedCountry)) {
            $stmtAttractionSelect->bindParam(':country', $selectedCountry, PDO::PARAM_STR);
        }
        if (!empty($insertedSearch)) {
            $searchTerm = '%' . $insertedSearch . '%';
            $stmtAttractionSelect->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        }
        $stmtAttractionSelect->execute();
        if ($stmtAttractionSelect->rowCount() > 0) {
            while ($row = $stmtAttractionSelect->fetch(PDO::FETCH_ASSOC)) {
                $attractionName = $row["attraction_name"];
                $attractionId = $row["id_attraction"];
                $attractionType = $row["type"];
                $cityData = getCityData($pdo, $attractionName);
                $pathData = getAttractionImagePath($pdo, $attractionId);
                echo "   
        <div class='col-md-4 mb-5'>
            <div class='card h-100 text-center border-5 border-light'style='background-image: url(" . $pathData["path"] . "); background-size: cover;'>
                <div class='card-body title'>    
                    <h2>{$attractionName}</h2>
                    <p>City: {$cityData["city_name"]} </p>
                    <p>Type: {$attractionType} </p>
                    
                    ";
                if (!empty($id_user)) {
                    echo "
                    <form method='post' action='attraction_details.php'>
                        <input type='hidden' name='attraction_id' value='{$attractionId}'>
                        <input type='submit' class='btn btn-light border-3 border-dark' value='More information'>
                    </form>";
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

