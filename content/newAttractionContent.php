<?php
$id_organization = '';
if (isset($_SESSION['username']) && isset($_SESSION['id_organization']) && is_int($_SESSION['id_organization'])) {
    $id_organization = $_SESSION['id_organization'];
}

if (!isset($_SESSION['username']) || !isset($_SESSION['id_organization']) || !is_int($_SESSION['id_organization'])) {
    redirection('index.php?e=0');
}
?>

<div class='container px-4 px-lg-5'>

    <h1 class='font-weight-light my-2 text-center text-decoration-underline'>New tour</h1>
    <br>
    <form method="post" class="form-control" action="form_action.php" enctype="multipart/form-data">
        <input type='hidden' name='action' value='newAttraction'>
        <label for="attraction_name">What is the attraction called?</label>
        <input type="text" name="attraction_name" id="attraction_name" class="form-control"
               placeholder="Attraction name"><br>
        <label for="type">What type of attraction?</label>
        <?php
        echo '<select name="type" id="type" class="form-select">';
        $sql = "SHOW COLUMNS FROM attractions LIKE 'type'";
        $query = $pdo->prepare($sql);
        $query->execute();
        $types = $query->fetch(PDO::FETCH_ASSOC);
        $enum_values = explode("','", substr($types['Type'], 6, -2));
        sort($enum_values);

        foreach ($enum_values as $value) {
            echo '<option value="' . $value . '">' . $value . '</option>';
        }
        ?>
        </select><br>

        <label for="description">Describe the attraction!</label>
        <br>
        <textarea rows="5" name="description" id="description" class="form-control"></textarea>

        <label for="address">Type address here</label>
        <br>
        <input type="text" name="address" id="address" class="form-control" placeholder="Attraction address">

        <label for="images">Images about attraction</label>
        <input type="file" id="images" name="images[]" class="form-control" multiple accept="image/jpeg">
        <br>
        <div class="text-center">
            <button type='submit' class='btn btn-success border-3 border-dark text-center btn-lg'>New attraction
            </button>
        </div>
        <?php
        require_once 'error.php';
        ?>
    </form>
</div>