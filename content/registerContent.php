<form action="web.php" method="post" id="registerForm">
    <label for="registerEmail">Email</label>
    <input
            type="text" id="registerEmail" name="email">
    <small></small>
    <br>
    <label for="registerFirstname">first name</label>
    <input
            type="text" id="registerFirstname" name="firstname">
    <small></small>
    <br>
    <label for="registerLastname">last name</label>
    <input
            type="text" id="registerLastname" name="lastname">
    <small></small>
    <br>
    <label for="registerPassword">password</label>
    <input
            type="password" id="registerPassword" name="password">
    <small></small>
    <br>
    <span id="strengthDisp" class="badge displayBadge"></span>
    <label for="registerPasswordConfirm">password confirm</label>
    <input
            type="password" id="registerPasswordConfirm" name="passwordConfirm">
    <small></small>
    <br>
    <input type="hidden" name="action" value="register">
    <button type="submit" id="register">Register</button>

    <a href="signIn.php" style="text-decoration: none">Login page</a>
</form>
