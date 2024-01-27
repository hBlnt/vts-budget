<div class="container px-4 px-lg-5">
    <div class="row gx-4 gx-lg-5 my-5 justify-content-center text-center">
        <div class="col-lg-5 text-center">
            <h1 class="font-weight-light"></h1>
        </div>
    </div>
    <div class="col-lg-12 pb-4 text-center">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5 mx-auto ">
            <form action="web.php" method="post" name="forget" id="forgetForm" class="form-control">
                <div>
                    <label for="forgetEmail">Email Address</label>
                    <input type="text" id="forgetEmail"
                           name="email" class="form-control"/>
                    <small></small>
                </div>
                <input type="hidden" name="action" value="forget">
                <br>
                <button type="submit" class="border border-dark btn btn-success">Reset password</button>
            </form>
            <?php
            require_once 'error.php';
            ?>
        </div>
    </div>
</div>
