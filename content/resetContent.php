<?php
$token = null;
if (isset($_GET['token'])) {
    $token = trim($_GET['token']);
}
?>
<div class="container px-4 px-lg-5">
    <div class="row gx-4 gx-lg-5 my-5 justify-content-center text-center">
        <div class="col-lg-5 text-center">
            <h1 class="font-weight-light">Log in</h1>
        </div>
    </div>
    <div class="col-lg-12 pb-4 text-center">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5 mx-auto ">
            <form action="forget.php" method="post" id="resetForm">
                <div>
                    <label for="resetEmail">Email Address</label>
                    <input type="text" id="resetEmail"
                           name="resetEmail" class="form-control">
                    <small></small>
                </div>
                <div>
                    <label for="resetPassword">Password</label>
                    <input type="password" id="resetPassword"
                           name="resetPassword" placeholder="Password (min 8 characters)" class="form-control">
                    <small></small>
                </div>

                <div>
                    <label for="resetPasswordConfirm">Password Confirm</label>
                    <input type="password" id="resetPasswordConfirm"
                           name="resetPasswordConfirm" class="form-control">
                    <small></small>
                </div>
                <br>
                <input type="hidden" name="action" value="resetPassword">
                <input type="hidden" name="token" value="<?php echo $token ?>">
                <button type="submit" class="border border-dark btn btn-success">Send</button>
                <button type="reset" class="border border-dark btn btn-info">Cancel</button>
            </form>
            <?php

            require_once 'error.php';
            ?>
        </div>
    </div>
</div>