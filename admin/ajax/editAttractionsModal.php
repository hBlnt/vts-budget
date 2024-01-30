<?php

require_once '../../db_config.php';
require_once '../../functions.php';
$data = getAllAttractionDataByID($pdo,(int)$_GET['id']);

var_dump($data);
?>
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel">Edit attraction</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" novalidate class="row g-3 mt-3 mb-3 p-3" id="attraction">
                <div class="col-md-6 required">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" autofocus
                           value="<?= $data['attraction_name'] ?>">
                </div>

                <div class="col-md-6 required">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address"
                           value="<?= $data['address'] ?>">
                </div>
                <div class="required">
                    <label for="description" class="form-label">Description</label>
                    <textarea rows="6" class="form-control" id="description" name="description"><?= $data['description']?></textarea>
                </div>

                <div class="col-md-6 required">
                    <label for='type' class="form-label">Type</label>
                    <select name='type' id='type' class='form-select'>
                        <?php

                        $enum_values = getAllAttractionTypes($pdo);

                        foreach ($enum_values as $value) {
                        if ($data['type'] == $value)
                        echo '<option selected value="' . $value . '">' . $value . '</option>';
                        else
                        echo '<option value="' . $value . '">' . $value . '</option>';
                        }
                        ?>
                    </select><br>
                </div>

                <div class="col-md-6 required">
                    <label for="city" class="form-label">City</label>
                    <select class="form-select" name="city" id="city">
                        <?php
                        $cities = getAllData($pdo, 'cities');
                        foreach ($cities as $city) {

                            $id_city = $city['id_city'];
                            $city_name = $city['city_name'];
                            if ($data['id_city'] == $id_city)
                                echo '<option selected value="' . $id_city . '">' . $city_name . '</option>';
                            else
                                echo '<option value="' . $id_city . '">' . $city_name . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6 required">
                    <label for="organization" class="form-label">Organization</label>
                    <select class="form-select" name="organization" id="organization">
                        <?php
                        $organizations = getAllData($pdo, 'organizations');
                        foreach ($organizations as $organization) {

                            $id_org = $organization['id_organization'];
                            $org_name = $organization['org_name'];
                            if ($data['id_organization'] == $id_org)
                                echo '<option selected value="' . $id_org . '">' . $org_name . '</option>';
                            else
                                echo '<option value="' . $id_org . '">' . $org_name . '</option>';
                        }
                        ?>
                    </select>

                    <input type="hidden" name="op" value="update">
                    <input type="hidden" name="id_attraction" value="<?= (int)$_GET['id'] ?>">
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
