<?php
require_once 'db_config.php';
if (isset($_GET['token'])) {
    $token = trim($_GET['token']);
}
?>
<h2 class="fw-bold mb-2 ">Reset your password</h2>
<form action="forget.php" method="post" id="resetForm">
    <div>
        <label for="resetEmail">Email Address</label>
        <input type="text" id="resetEmail"
               name="resetEmail">
        <small></small>
    </div>
    <div>
        <label for="resetPassword">Password</label>
        <input type="password" id="resetPassword"
               name="resetPassword" placeholder="Password (min 8 characters)">
        <small></small>
    </div>

    <div>
        <label for="resetPasswordConfirm">Password Confirm</label>
        <input type="password" id="resetPasswordConfirm"
               name="resetPasswordConfirm">
        <small></small>
    </div>

    <input type="hidden" name="action" value="resetPassword">
    <input type="hidden" name="token" value="<?php echo $token ?>">
    <button type="submit">Send</button>
    <button type="reset">Cancel</button>
</form>