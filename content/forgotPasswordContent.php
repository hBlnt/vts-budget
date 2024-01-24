<form action="web.php" method="post" name="forget" id="forgetForm">
    <div>
        <label for="forgetEmail">Email Address</label>
        <input type="text" id="forgetEmail"
               name="email"/>
        <small></small>
    </div>
    <input type="hidden" name="action" value="forget">
    <button type="submit">Reset password</button>
</form>
