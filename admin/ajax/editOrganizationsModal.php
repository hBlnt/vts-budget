<?php

require_once '../../db_config.php';
require_once '../../functions.php';

$data = getOrganization($pdo, (int)$_GET['id']);

var_dump($data);
?>
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalToggleLabel">Edit organization</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" novalidate class="row g-3 mt-3 mb-3 p-3" id="organization">
                <div class="col-md-6 required">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" autofocus
                           value="<?= $data['org_name'] ?>">
                </div>

                <div class="col-md-6 required">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" autofocus
                           value="<?= $data['email'] ?>">
                </div>

                <div class="col-md-6 required">
                    <label for="email" class="form-label">City</label>
                    <select class="form-select" name="city" id="city">
                        <option value="<?= $data['id_city'] ?>"> <?= $data['city_name'] ?></option>

                        <?php
                        $unusedCities = getUnusedCities($pdo);
                        foreach ($unusedCities as $city) {
                            $id_city = $city['id_city'];
                            $city_name = $city['city_name'];
                            echo '<option value="' . $id_city . '">' . $city_name . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                           aria-describedby="passwordHelp">
                    <div id="passwordHelp" class="form-text">
                        Minimum length is 8 characters! If you leave it empty, it will not be changed.
                    </div>

                    <input type="hidden" name="op" value="update">
                    <input type="hidden" name="id_organization" value="<?= $data['id_organization'] ?>">
                </div>
                <div class="col-6 required">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" name="status" id="status">
                        <?php
                        if ($data['is_banned'] === 0)
                            echo '<option value="0" selected>Active</option>' . PHP_EOL;
                        else
                            echo '<option value="0">Active</option>' . PHP_EOL;

                        if ($data['is_banned'] === 1)
                            echo '<option value="1" selected>Blocked</option>' . PHP_EOL;
                        else
                            echo '<option value="1" >Blocked</option>' . PHP_EOL;
                        ?>
                    </select>
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
