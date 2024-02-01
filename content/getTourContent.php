<?php

require_once 'isUser.php';
$id_tour = $_POST['id_tour'] ?? '';
$tourData = getTableData($pdo, 'tours', 'id_tour', $id_tour, false);
$tourAttractionData = getTourData($pdo, $id_tour);
$cityNames = implode(',', array_map(function ($element) {
    return $element['city_name'];
}, getCityNames($pdo, $id_tour)));
$jsonTourData = json_encode($tourAttractionData);


echo "
<div class='container px-4 px-lg-5'>

    <h1 class='font-weight-light my-2 text-center text-decoration-underline'>{$tourData['tour_name']}</h1>
    <br>

    <div class='row gx-4 gx-lg-5 align-items-stretch my-5'>
        <div class='col-lg-5'>
            <div class='h-100 d-flex align-items-start'>
                <div>
                    <h1 class='font-weight-light my-2'>Travel type</h1>
                    <h3>The most ideal way to travel through this tour is: </h3>
                    <h4>{$tourData['tour_type']}</h4>
                </div>
            </div>
        </div>
        <div class='col-lg-5'>
            <div class='h-100 d-flex align-items-start'>
                <div>
                    <h2 class='font-weight-light my-2'>Tour was made on</h2>
                    <p>{$tourData['datetime']}</p>
                    <h2 class='font-weight-light my-2'>City(ies)</h2>
                    <p>{$cityNames}</p>
                </div>
            </div>
        </div>
    </div>
       
    <div class='row gx-4 gx-lg-5'>
        <h1 class='text-center'>Location(s)</h1>
    
        <div id='map' class='mb-5 border border-dark border-5 rounded-3'></div>
    </div>
    <div class='row gx-4 gx-lg-5'>
        <h1 class='text-center'>Attraction(s)</h1>
        
    </div>
        ";

?>

<script>
    var jsonData = <?php echo $jsonTourData; ?>;

    var innerCoordinates = Object.values(jsonData);
    console.log(innerCoordinates);
    console.log("-------------");

    const allNames = innerCoordinates.flatMap(obj => obj['attraction_name'] || []);

    const allCoordinates = innerCoordinates.flatMap(obj => obj['address'] || []);

    console.log(allCoordinates);
    console.log(allNames);
    console.log("----------------------------");

    const numericCoordinates = allCoordinates.map(coord => coord.split(',').map(parseFloat));

    console.log(numericCoordinates);

    let latitudes = [];
    let longitudes = [];
    let countCoordinates = 0;
    let zoomLevel = 0;
    for (let i = 0; i < numericCoordinates.length; i++) {
        countCoordinates++;
        let numericCoordinate = [i];
        for (let j = 0; j <= numericCoordinate.length; j++) {
            if (j === 0)
                latitudes.push(numericCoordinates[i][j]);
            else
                longitudes.push(numericCoordinates[i][j]);
        }
    }
    let latitudesSum = 0.00;
    for (let i = 0; i < latitudes.length; i++)
        latitudesSum += latitudes[i];
    let longitudesSum = 0.00;
    for (let i = 0; i < longitudes.length; i++)
        longitudesSum += longitudes[i];
    let middleLatitude = latitudesSum / countCoordinates;
    let middleLongitude = longitudesSum / countCoordinates;

    console.log("----------------------------");

    console.log(`middle coord: [${middleLatitude},${middleLongitude}]`);
    const map = L.map('map');


    map.setView([middleLatitude, middleLongitude], 11);
    //48.804806, 2.120333
    //latitude, longitude
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    console.log(numericCoordinates);

    let counter = 0;
    for (let value of numericCoordinates) {
        let name = allNames[counter];
        L.marker([value[0], value[1]], {title: name})
            .bindPopup("<h4>" + name + "</h4>")
            .addTo(map);
        counter++;
    }

    function map_range(value, low1, high1, low2, high2) {
        return low2 + (high2 - low2) * (value - low1) / (high1 - low1);
    }

</script>

<?php
echo "<div class='row gx-4 gx-lg-5'>";

foreach ($tourAttractionData as $attractionData) {
    $path = getAttractionImagePath($pdo, $attractionData['id_attraction']);
    echo "   
   
        <div class='mx-auto col-sm-8 col-md-6 col-lg-4 mb-5'>
            <div class='card h-100 text-center border-5 border-light'style='background-image: url(" . $path . "); background-size: cover; background-position: center'>
                <div class='card-body d-flex flex-column m-4'>    
                 <h2 class='title pb-5'>" . $attractionData["attraction_name"] . "</h2>
                 
                                 
             </div>
         </div>
     </div>
    ";


}
?>
</div>

</div>
