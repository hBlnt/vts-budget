<div class="container px-4 px-lg-5">
    <div class="row gx-4 gx-lg-5 align-items-center my-5">
        <div class="col-lg-5">
            <h1 class="font-weight-light">CITIES</h1>
            <p>Search by any city</p>
        </div>
    </div>
    <div class="row gx-4 gx-lg-5">
        <?php
        $pdo = connectDatabase($dsn, $pdoOptions);
        $sqlCitySelect = "SELECT * FROM `cities` ORDER BY `cities`.`city_name` ASC;";
        $stmtCitySelect = $pdo->prepare($sqlCitySelect);
        $stmtCitySelect->execute();
        if ($stmtCitySelect->rowCount() > 0) {
            while ($row = $stmtCitySelect->fetch(PDO::FETCH_ASSOC)) {
                $image = $row["city_image"];
                $cityName= $row["city_name"];
                echo "   
        <div class='col-md-4 mb-5'>
            <div class='card h-100 text-center'style='background-image: url(" . $image . "); background-size: cover;'>
                <div class='card-body'>    
                    <a href='attractions.php?city={$cityName}' class='title'><h2>{$cityName}</h2></a>
                </div>
            </div>
        </div>
    ";
            }

        }
        ?>
    </div>
</div>

