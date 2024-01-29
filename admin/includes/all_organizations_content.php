<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="link-dark"">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $titles[$page] ?></li>
        </ol>
    </nav>

    <table id="allOrganizations" class="table table-hover display" style="width:100%">
        <thead>
        <tr>
            <th>No</th>
            <th>Org name</th>
            <th>E-mail</th>
            <th>City</th>
            <th>Status</th>
            <th>Date time</th>
            <th>Options</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>No</th>
            <th>Org name</th>
            <th>E-mail</th>
            <th>City</th>
            <th>Status</th>
            <th>Date time</th>
            <th>Options</th>
        </tr>
        </tfoot>
    </table>

    <div class="modal fade" id="organizationsModal" aria-hidden="true" aria-labelledby="organizationsModal" tabindex="-1">

    </div>
    <?php
    if (isset($_GET['m']) and array_key_exists($_GET['m'], $messagesAdmin[$page])) {
        echo '<div class="alert alert-' . $messagesAdmin[$page][$_GET['m']]['style'] . ' alert-dismissible fade show" role="alert" id="message">' . $messagesAdmin[$page][$_GET['m']]['text'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
    ?>
</div>

