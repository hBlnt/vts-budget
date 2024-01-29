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
                    <label for="loginUsername">Username</label>
                    <br>
                    <input type="text" id="loginUsername" name="username" class="form-control">
                    <small></small>
                    <br>
                </div>

                <div>
                    <label for="loginPassword">Password</label>
                    <br>
                    <input type="password" id="loginPassword" name="password" class="form-control" >
                    <small></small>
                </div>
                <br>


                <input type="hidden" name="action" value="login">
                <button type="submit" class="border border-dark btn btn-success">Log in</button>
                <br><br>
            </form>
            <?php
            if (isset($_GET['m']) and array_key_exists($_GET['m'], $messagesAdmin[$page])) {
                echo '<div class="alert alert-' . $messagesAdmin[$page][$_GET['m']]['style'] . ' alert-dismissible fade show" role="alert" id="message">' . $messagesAdmin[$page][$_GET['m']]['text'] . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            }
            ?>
        </div>
    </div>
</div>
