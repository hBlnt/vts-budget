<div class="container px-4 px-lg-5">
    <div class="row gx-4 gx-lg-5 my-5 justify-content-center text-center">
        <div class="col-lg-5 text-center">
            <h1 class="font-weight-light">Log in</h1>
        </div>
    </div>
    <div class="col-lg-12 pb-4 text-center">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5 mx-auto ">
            <form action="web.php" method="post" id="loginForm" class="text-center form-control">
                <div>
                    <br>
                    <label for="loginUsername">E-mail</label>
                    <br>
                    <input type="text" id="loginUsername" name="username" class="form-control" placeholder="Your e-mail goes here">
                    <small></small>
                    <br>
                </div>

                <div>
                    <label for="loginPassword">Password</label>
                    <br>
                    <input type="password" id="loginPassword" name="password" class="form-control" placeholder="sesame open">
                    <small></small>
                </div>

                <p><a href="forgotPassword.php">Forgot password?</a></p>

                <input type="hidden" name="action" value="login">
                <button type="submit" class="border border-dark btn btn-success">Log in</button>
                <br><br>
            </form>
            <?php

            require_once 'error.php';
            ?>
        </div>
    </div>
</div>