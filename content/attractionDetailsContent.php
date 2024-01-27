<?php
$id_user = '';
if (isset($_SESSION['username']) && isset($_SESSION['id_user']) && is_int($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
}
$attraction_id = $_POST['attraction_id'] ?? '';

$attraction = getTableData($pdo, 'attractions', 'id_attraction', $attraction_id,false);
$attractionName = $attraction['attraction_name'];
$images = getAttractionImages($pdo, $attraction_id);
echo "

<div class='container px-4 px-lg-5'>

    <h1 class='font-weight-light my-2 text-center text-decoration-underline'>{$attractionName}</h1>

<div class='row gx-4 gx-lg-5 align-items-stretch my-5'>
    <div class='col-lg-5'>
        <div class='h-100 d-flex align-items-start'>
            <div>
                <h2 class='font-weight-light my-2'>Description</h2>
                <p>{$attraction['description']}</p>
            </div>
        </div>
    </div>
    <div class='col-lg-5'>
        <div class='h-100 d-flex align-items-start'>
            <div>
                <h2 class='font-weight-light my-2'>Attraction type</h2>
                <p>{$attraction['type']}</p>
                <h2 class='font-weight-light my-2'>Address</h2>
                <p>{$attraction['address']}</p>
                ";
if(!isFavouriteAttractionExist($pdo,$attraction['id_city'],$id_user))
{
    echo"
                <form method='post' class='text-center' action='form_action.php'>
                
                    <input type='hidden' name='action' value='makeFavourite'>
                    <input type='hidden' name='id_attraction' value='{$attraction_id}'>
                    <input type='submit' value='Make favourite' class='btn btn-danger'>
                </form>
                ";
}
                echo"
            </div>
        </div>
    </div>
</div>


<div class='row gx-4 gx-lg-5 align-items-stretch my-5'>
      <div class='h-100 d-flex  justify-content-center text-center'>
          <div>
              <h2 class='font-weight-light my-2'>Image(s)</h2>
          </div>
      </div>
</div>
            ";
$imageCounter = 0;
echo "<div class='row gx-4 gx-lg-5 align-items-stretch my-5'>";
foreach ($images as $image) {
    $imageCounter++;

    echo "<div class='col-lg-6 h-100 d-flex align-items-start text-center'>";
    echo "<div>";
    echo "<img src='{$image['path']}' alt='{$attractionName}' class='img-thumbnail rounded img-fluid imgsmall'>";
    echo "</div>";
    echo "</div>";

    if ($imageCounter % 2 == 0) {
        echo "</div>";
        echo "<div class='row gx-4 gx-lg-5 align-items-stretch my-5'>";
    }
}

if ($imageCounter % 2 != 0) {
    echo "</div>";
} ?>
</div>
</div>
