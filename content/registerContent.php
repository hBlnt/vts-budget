<div class="container px-4 px-lg-5">
    <div class="row gx-4 gx-lg-5 my-5 justify-content-center text-center">
        <div class="col-lg-5 text-center">
            <h1 class="font-weight-light">Register</h1>
        </div>
    </div>
    <div class="col-lg-12 pb-4 text-center">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5 mx-auto ">
            <form action="web.php" method="post" id="registerForm" class="text-center form-control">
                <div>
                    <label for="registerEmail">Email</label>
                    <input
                            type="text" id="registerEmail" name="email" class="form-control">
                    <small></small>
                    <br>
                </div>
                <div>
                    <label for="registerFirstname">First name</label>
                    <input
                            type="text" id="registerFirstname" name="firstname" class="form-control">
                    <small></small>
                    <br>
                </div>
                <div>
                    <label for="registerLastname">Last name</label>
                    <input
                            type="text" id="registerLastname" name="lastname" class="form-control">
                    <small></small>
                    <br>
                </div>
                <div>
                    <label for="registerPassword">Password</label>
                    <input
                            type="password" id="registerPassword" name="password" class="form-control">
                    <small></small>
                    <br>
                    <span id="strengthDisp" class="badge displayBadge"></span>
                </div>
                <div>
                    <label for="registerPasswordConfirm">Password confirm</label>
                    <input
                            type="password" id="registerPasswordConfirm" name="passwordConfirm" class="form-control">
                    <small></small>
                    <br>
                </div>
                <input type="hidden" name="action" value="register">
                <button type="submit" id="register" class="border border-dark btn btn-success">Register</button>
                <br><br>

                <a href="login.php"  class="border border-dark btn btn-info">Login page</a>
            </form>

            <?php
            require_once 'error.php';
            ?>
        </div>
    </div>
</div>