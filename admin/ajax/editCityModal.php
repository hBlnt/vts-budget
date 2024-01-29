<?php

require_once '../../db_config.php';
require_once '../../functions.php';

$data = getTableData($pdo, 'cities', 'id_city', (int)$_GET['id'], false);

//var_dump($data);
?>
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel">Edit city</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" novalidate class="row g-3 mt-3 mb-3 p-3" id="city" enctype="multipart/form-data">
                <div class="col-md-6 required">
                    <label for="name" class="form-label">City Name</label>
                    <input type="text" class="form-control" id="name" name="name" autofocus
                           value="<?= $data['city_name'] ?>">
                </div>

                <div class="col-md-6 required">
                    <label for="country" class="form-label">Country</label>
                    <?php

                    $sql = "SHOW COLUMNS FROM cities LIKE 'country'";
                    $query = $pdo->prepare($sql);
                    $query->execute();
                    $countries = $query->fetch(PDO::FETCH_ASSOC);
                    $enum_values = explode("','", substr($countries['Type'], 6, -2));
                    sort($enum_values);

                    ?>
                    <select class="form-select" name="country" id="country">

                        <?php

                        foreach ($enum_values as $value) {
                            if ($value === $data['country'])
                                echo '<option selected value="' . $value . '">' . $value . '</option>';
                            else
                                echo '<option value="' . $value . '">' . $value . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-12 text-center">
                    <p>Thumbnail</p>
                    <div class="image-upload">
                        <label for="file">
                            <img src="../<?= $data['city_image'] ?>" width="256"
                                 class="border border-dark rounded p-1 mb-1"/>
                        </label>
                        <br>
                        <input type="file" class="form-control" id="file" name="file" aria-describedby="imageHelp"
                               accept="image/jpeg">
                        <div id="imageHelp" class="form-text">
                            Upload only 1 JPG image(if you dont upload image doesnt get changed)!
                        </div>
                    </div>

                    <input type="hidden" name="op" value="update">
                    <input type="hidden" name="id_city" value="<?= $data['id_city'] ?>">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Send</button>
                    <button type="reset" class="btn btn-primary">Cancel</button>
                </div>
            </form>
            <div id="message" class="alert hide"></div>
            <?php
            /*
            if (isset($_GET['m']) and array_key_exists($_GET['m'], $messages[$page])) {
                echo '<div class="alert alert-' . $messages[$page][$_GET['m']]['style'] . ' alert-dismissible fade show" role="alert" id="messageAjax">' . $messages[$page][$_GET['m']]['text'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }*/
            ?>
        </div>
    </div>
</div>
