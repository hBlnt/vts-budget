<div class="container px-4 px-lg-5">
    <div class="row gx-4 gx-lg-5 justify-content-center text-center my-5">
        <div class="col-lg-5">
            <h1 class="font-weight-light">CITIES</h1>
            <p>Search by any city</p>
        </div>
    </div>
    <div class="row gx-4 gx-lg-5">
        <?php
        $sql = "SELECT c.city_image, c.city_name FROM `cities` c
         INNER JOIN organizations o ON c.id_city = o.id_city
         WHERE o.is_banned = 0
         ORDER BY c.city_name ASC;";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $image = $row["city_image"];
                $cityName= $row["city_name"];
                echo "   
        <div class=' col-md-6 col-lg-4 mb-5'>
            <div class='card h-100 text-center border-5 border-light'style='background-image: url(" . $image . "); background-size: cover;'>
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

