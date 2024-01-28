<?php
session_start();
require_once "db_config.php";
require_once "functions.php";

$password = "";
$passwordConfirm = "";
$firstname = "";
$lastname = "";
$email = "";

$score = "";
$action = "";

$referer = $_SERVER['HTTP_REFERER'];


$action = $_POST["action"];

$blockedDomain = "org.com";
if ($action != "" and in_array($action, $actions) and strpos($referer, SITE) !== false) {


    switch ($action) {
        case "login":

            $_SESSION['firstname'] = '';
            $_SESSION['lastname'] = '';

            $username = trim($_POST["username"]);
            $password = trim($_POST["password"]);

            if (!empty($username) and !empty($password)) {
                $data = checkUserLogin($pdo, $username, $password);
                if ($data and is_int($data['id_user'])) {
                    $_SESSION['username'] = $username;
                    $_SESSION['id_user'] = $data['id_user'];
                    redirection('index.php');
                } else if ($data and is_int($data['id_organization'])) {
                    $_SESSION['username'] = $username;
                    $_SESSION['id_organization'] = $data['id_organization'];
                    redirection('index.php');

                } else {
                    redirection('login.php?e=1');
                }

            } else {
                redirection('login.php?e=1');
            }
            break;


        case "register" :



            if (isset($_POST['firstname'])) {
                $firstname = trim($_POST["firstname"]);
            }

            if (isset($_POST['lastname'])) {
                $lastname = trim($_POST["lastname"]);
            }

            if (isset($_POST['password'])) {
                $password = trim($_POST["password"]);
            }

            if (isset($_POST['passwordConfirm'])) {
                $passwordConfirm = trim($_POST["passwordConfirm"]);
            }

            if (isset($_POST['email'])) {
                $email = trim($_POST["email"]);
            }

            if (empty($firstname)) {
                redirection('registration.php?e=4');
            }

            if (empty($lastname)) {
                redirection('registration.php?e=4');
            }

            if (empty($password)) {
                redirection('registration.php?e=9');
            }

            if (strlen($password) < 8) {
                redirection('registration.php?e=10');
            }

            if (empty($passwordConfirm)) {
                redirection('registration.php?e=9');
            }

            if ($password !== $passwordConfirm) {
                redirection('registration.php?e=7');
            }

            if (empty($email) or !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                redirection('registration.php?e=8');
            }
            list($name,$domain) = explode("@",$email);
            if($domain === $blockedDomain)
                redirection('registration.php?e=36');

            //Making sure that the e-mail is not taken by either a user or a trainer
            if (!existsUser($pdo, $email)) {
                $token = createToken(20);
                if ($token) {
                    $id_user = registerUser($pdo, $password, $firstname, $lastname, $email, $token);
                    try {
                        $body = "Your username is $email. To activate your account click on the <a href=" . SITE . "active.php?token=$token   >link</a>";
                        sendEmail($pdo, $email, $emailMessages['register'], $body, $id_user);
                        redirection("register_authentication.php?e=3");
                    } catch (Exception $e) {
                        error_log("****************************************");
                        error_log($e->getMessage());
                        error_log("file:" . $e->getFile() . " line:" . $e->getLine());
                        redirection("register_authentication.php?e=11");
                    }
                }
            } else {
                redirection('registration.php?e=2');
            }

            break;


        case "forget" :
            $email = trim($_POST["email"]);

            list($name,$domain) = explode("@",$email);

            if($domain === $blockedDomain)
                redirection('forgotPassword.php?e=36');

            if (!empty($email) and getUserData($pdo, 'id_user', 'email', $email)) {
                $token = createToken(20);
                if ($token) {
                    setForgottenToken($pdo, 'users', $email, $token);
                    $id_user = getUserData($pdo, 'id_user', 'email', $email);
                    try {
                        $body = "To start the process of changing password, visit <a href=" . SITE . "forget.php?token=$token>link</a>.";
                        sendEmail($pdo, $email, $emailMessages['forget'], $body, $id_user);
                        redirection('forgotPassword.php?e=13');
                    } catch (Exception $e) {
                        error_log("****************************************");
                        error_log($e->getMessage());
                        error_log("file:" . $e->getFile() . " line:" . $e->getLine());
                        redirection("forgotPassword.php?e=11");
                    }
                } else {
                    redirection('forgotPassword.php?e=14');
                }
            } else {
                redirection('forgotPassword.php?e=13');
            }
            break;
        default:
            redirection('login.php?e=1');
            break;


    }

} else {
    redirection('login.php?e=1');
}
