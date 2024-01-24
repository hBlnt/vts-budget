<form action="web.php" method="post" id="loginForm">
    <div>
        <label for="loginUsername">E-mail</label>
        <input type="text" id="loginUsername" name="username">
        <small></small>
    </div>

    <div>
        <label for="loginPassword">Password</label>
        <input type="password" id="loginPassword" name="password">
        <small></small>
    </div>

    <p><a href="forgotPassword.php">Forgot password?</a></p>

    <input type="hidden" name="action" value="login">
    <button type="submit">Log in</button>
</form>
