<?php

require_once 'isUser.php';
$id_tour = $_POST['id_tour'] ?? '';
$tourData = getTableData($pdo, 'tours', 'id_tour', $id_tour, false);
$tourAttractionData = getTourData($pdo, $id_tour);
$cityNames = implode(',',array_map(function($element) {
    return $element['city_name'];
}, getCityNames($pdo,$id_tour)));

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
        <h1>Attractions</h1>
       
    <div class='row gx-4 gx-lg-5'>

        ";
foreach ($tourAttractionData as $attractionData) {
    $path = getAttractionImagePath($pdo, $attractionData['id_attraction']);
    echo "   
   
        <div class=' col-md-6 col-lg-4 mb-5'>
            <div class='card h-100 text-center border-5 border-light'style='background-image: url(" . $path . "); background-size: cover;'>
                <div class='card-body title d-flex flex-column'>    
                 <h2>" . $attractionData["attraction_name"] . "</h2>
                 
                 <p class='mt-auto'>Address: <br>" . $attractionData["address"] . "</p>
                                 
             </div>
         </div>
     </div>
    ";


}
?>
</div>

</div>
