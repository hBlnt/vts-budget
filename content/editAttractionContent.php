<?php
$id_organization = '';
if (isset($_SESSION['username']) && isset($_SESSION['id_organization']) && is_int($_SESSION['id_organization'])) {
    $id_organization = $_SESSION['id_organization'];
}

if (!isset($_SESSION['username']) || !isset($_SESSION['id_organization']) || !is_int($_SESSION['id_organization'])) {
    redirection('index.php?e=0');
}
$id_attraction = $_POST["id_attraction"] ?? "";

if(empty($id_attraction))
    redirection('attractions.php?e=0');

$attraction_data = getTableData($pdo, 'attractions', 'id_attraction', $id_attraction, false);

if (!$attraction_data)
    redirection('attractions.php?e=0');



echo "

<div class='container px-4 px-lg-5'>

    <h1 class='font-weight-light my-2 text-center text-decoration-underline'>Edit attraction</h1>
    <br>
    <form method='post' class='form-control' action='form_action.php' enctype='multipart/form-data'>
        <input type='hidden' name='action' value='editAttraction'>
        <input type='hidden' name='id_attraction' value='{$id_attraction}'>
        <label for='attraction_name'>Attraction name</label>
        <input type='text' name='attraction_name' id='attraction_name' class='form-control'
               placeholder='Attraction name' value='{$attraction_data['attraction_name']}'><br>
        <label for='type'>Type</label>
       <select name='type' id='type' class='form-select'>
       ";

$enum_values = getAllAttractionTypes($pdo);

foreach ($enum_values as $value) {
    if ($attraction_data['type'] == $value)
        echo '<option selected value="' . $value . '">' . $value . '</option>';
    else
        echo '<option value="' . $value . '">' . $value . '</option>';
}
?>
</select><br>

<?php
$image_names = getImageNames($pdo,$id_attraction);
//var_dump($image_names);
echo "
        <label for='description'>Describe the attraction!</label>
        <br>
        <textarea rows='5' name='description' id='description' class='form-control'>{$attraction_data['description']}</textarea>

        <label for='address'>Type address here</label>
        <br>
        <input type='text' name='address' id='address' class='form-control' placeholder='Attraction address' value='{$attraction_data['address']}'>
        
       <!-- <label for='images'>Images about attraction</label>-->
       <!-- <input type='file' id='images' name='images[]' class='form-control' multiple accept='image/jpeg'>-->
        <br>
        <div class='text-center'>
            <button type='submit' class='btn btn-success border-3 border-dark text-center btn-lg'>Edit</button>
        </div>
        ";
require_once 'error.php';
?>
</form>
</div>
