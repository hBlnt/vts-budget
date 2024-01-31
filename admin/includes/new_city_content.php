<div class="container ">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="link-dark"">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $titles[$page] ?></li>
        </ol>
    </nav>

    <form method="post" action="create_city.php" novalidate class="row g-3 mt-3 mb-3 p-3" id="city_form"
          enctype="multipart/form-data">
        <div class="col-md-2 required">
            <label for="name" class="form-label">City name</label>
            <input type="text" class="form-control" id="name" name="name" autofocus>
        </div>
        <div class="col-md-2 required">
            <label for="country" class="form-label">Country</label>
            <select class="form-select" aria-label="Default select example" id="country" name="country">
                <?php
                $countries = getAllCountries($pdo);
                echo '<option value="choose" selected>Choose</option>' . PHP_EOL;
                foreach ($countries as $country) {
                    echo '<option value="' . $country . '">' . $country . '</option>' . PHP_EOL;
                }
                ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="image" class="form-label">Thumnail image</label>
            <input type="file" class="form-control" id="image" name="image" aria-describedby="imageHelp"
                   accept="image/jpeg">
            <div id="imageHelp" class="form-text">
                Upload only 1 JPG image!
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Send</button>
            <button type="reset" class="btn btn-primary">Cancel</button>
        </div>
    </form>
    <?php
    if (isset($_GET['m']) and array_key_exists($_GET['m'], $messagesAdmin[$page])) {
        echo '<div class="alert alert-' . $messagesAdmin[$page][$_GET['m']]['style'] . ' alert-dismissible fade show" role="alert" id="message">' . $messagesAdmin[$page][$_GET['m']]['text'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
    ?>
</div>

