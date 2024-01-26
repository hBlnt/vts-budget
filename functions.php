<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once "db_config.php";
$pdo = connectDatabase($dsn, $pdoOptions);

/** Function tries to connect to database using PDO
 * @param string $dsn
 * @param array $pdoOptions
 * @return PDO
 */
function connectDatabase(string $dsn, array $pdoOptions): PDO
{

    try {
        $pdo = new PDO($dsn, PARAMS['USER'], PARAMS['PASS'], $pdoOptions);

    } catch (\PDOException $e) {
        var_dump($e->getCode());
        throw new \PDOException($e->getMessage());
    }

    return $pdo;
}

/**
 * Function redirects users to given url
 *
 * @param string $url
 */
function redirection($url)
{
    header("Location:$url");
    exit();
}

/**
 * Function checks that login parameters exists in users_web table
 *
 * @param PDO $pdo
 * @param string $email
 * @param string $enteredPassword
 * @return array
 */
function checkUserLogin(PDO $pdo, string $email, string $enteredPassword): array
{
    $sqlUser = "SELECT id_user, password,firstname,lastname FROM users WHERE email=:email AND active=1 AND is_banned = 0 LIMIT 0,1";

    $stmtUser = $pdo->prepare($sqlUser);
    $stmtUser->bindParam(':email', $email, PDO::PARAM_STR);

    $data = [];

    $stmtUser->execute();
    if ($stmtUser->rowCount() > 0) {
        $result = $stmtUser->fetch(PDO::FETCH_ASSOC);

    }

    if ($stmtUser->rowCount() > 0) {

        $registeredPassword = $result['password'];

        if (password_verify($enteredPassword, $registeredPassword)) {
            $data['id_user'] = $result['id_user'];
        }
    }

    return $data;
}


/**
 * Function checks that users exists in users table
 * @param PDO $pdo
 * @param string $email
 * @return bool
 */
function existsUser(PDO $pdo, string $email): bool
{

    $sql = "SELECT id_user FROM users WHERE email=:email AND (registration_token_expiry>now() OR active ='1') LIMIT 0,1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function registerUser(PDO $pdo, string $password, string $firstname, string $lastname, string $email, string $token): int
{

    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users(password,firstname,lastname,email,registration_token, registration_token_expiry,active)
            VALUES (:passwordHashed,:firstname,:lastname,:email,:token,DATE_ADD(now(),INTERVAL 1 DAY),0)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':passwordHashed', $passwordHashed, PDO::PARAM_STR);
    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();

    // http://dev.mysql.com/doc/refman/5.6/en/date-and-time-functions.html

    return $pdo->lastInsertId();

}

/** Function creates random token for given length in bytes
 * @param int $length
 * @return string|null
 */
function createToken(int $length): ?string
{
    try {
        return bin2hex(random_bytes($length));
    } catch (\Exception $e) {
        // c:xampp/apache/logs/
        error_log("****************************************");
        error_log($e->getMessage());
        error_log("file:" . $e->getFile() . " line:" . $e->getLine());
        return null;
    }
}

function editUser(PDO $pdo, string $password, string $firstname, string $lastname, string $age, string $phone, string $id): bool
{
    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET ";

    $updateFields = [];
    $params = [];

    if (!empty($password)) {
        $updateFields[] = "password = :passwordHashed";
        $params[':passwordHashed'] = $passwordHashed;
    }

    if (!empty($firstname)) {
        $updateFields[] = "firstname = :firstname";
        $params[':firstname'] = $firstname;
    }

    if (!empty($lastname)) {
        $updateFields[] = "lastname = :lastname";
        $params[':lastname'] = $lastname;
    }

    if (empty($updateFields)) {
        return false; // No fields to update
    }

    $sql .= implode(", ", $updateFields);
    $sql .= " WHERE id_user = :id";
    $params[':id'] = $id;

    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute($params);
        return true;
    } catch (PDOException $ex) {
        error_log("******************FUNCTIONERROR**********************");
        error_log($ex->getMessage());
        error_log("file:" . $ex->getFile() . " line:" . $ex->getLine());
        return false;
    }

}
const GMailUSEREmail = 'personaltrainerhelp2023@gmail.com'; // your username on gmail
const GoogleAppsPassword = 'ybyvuesekrtnjjbb'; // you password for created APP

function sendEmail(PDO $pdo, string $email, array $emailData, string $body, int $id_user): void
{

    $toEmail = $email;
    $subject = $emailData['subject'];
    $from = 'personaltrainerhelp2023@gmail.com';
    $fromName = 'Personal Trainer';


    try {
        $phpmailer = new PHPMailer(true);
        $phpmailer->IsSMTP();
        $phpmailer->SMTPDebug = 0;
        $phpmailer->SMTPAuth = true;
        $phpmailer->SMTPSecure = 'ssl';
        $phpmailer->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $phpmailer->Host = 'smtp.gmail.com';
        $phpmailer->Port = 465;
        $phpmailer->Username = GMailUSEREmail;
        $phpmailer->Password = GoogleAppsPassword;
        $phpmailer->SetFrom($from, $fromName);
        $phpmailer->isHTML(true);
        $phpmailer->Subject = $subject;
        $phpmailer->Body = $body;

        $phpmailer->AltBody = $emailData['altBody'];
        $phpmailer->AddAddress($toEmail);
        $phpmailer->send();
    } catch (Exception $e) {
        $message = "Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}";
        addEmailFailure($pdo, $id_user, $message);
    }

}

/**
 * @param PDO $pdo
 * @param string $email
 * @return bool
 * @throws Exception
 */
function sendForgetPasswordToken(PDO $pdo, string $email): bool
{
    $token = createToken(20);


    return true;
}


/** Function inserts data in database for e-mail sending failure
 * @param PDO $pdo
 * @param int $id_user
 * @param string $message
 * @return void
 */
function addEmailFailure(PDO $pdo, int $id_user, string $message): void
{
    $sql = "INSERT INTO user_email_failures (id_user, message, date_time_added)
            VALUES (:id_user,:message, now())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    $stmt->execute();

}

function getUserData(PDO $pdo, string $data, string $field, string $value): string
{
    $sql = "SELECT $data as data FROM users WHERE $field=:value LIMIT 0,1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $data = '';

    if ($stmt->rowCount() > 0) {
        $data = $result['data'];
    }

    return $data;
}

function setForgottenToken(PDO $pdo, string $table, string $email, string $token): void
{
    $sql = "UPDATE $table SET forgotten_password_token = :token, forgotten_password_expiry = DATE_ADD(now(),INTERVAL 6 HOUR) WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
}

function getCityData(PDO $pdo, string $attractionName): array
{
    $sql = "SELECT c.id_city,c.city_name,c.country 
    FROM cities c
    INNER JOIN attractions a ON c.id_city = a.id_city 
    WHERE a.attraction_name = :attraction_name";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':attraction_name', $attractionName, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAttractionTypes(PDO $pdo): array
{
    $sql = 'SELECT DISTINCT type FROM attractions';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTableData(PDO $pdo, string $table_name, string $id_name, int $id, bool $fetchAll): array|bool
{
    $sql = "SELECT * FROM $table_name WHERE $id_name = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($fetchAll)
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    else
        return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAttractionImagePath(PDO $pdo, string $id_attraction): array
{
    $sql = "SELECT i.path FROM 
            images i
            INNER JOIN attractions_images ai ON  i.id_image = ai.id_image
            INNER JOIN attractions a ON ai.id_attraction = a.id_attraction
            WHERE a.id_attraction = :id_attraction LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_attraction', $id_attraction, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAttractionImages(PDO $pdo, $id_attraction): array
{
    $sql = "SELECT i.path FROM 
            images i
            INNER JOIN attractions_images ai ON  i.id_image = ai.id_image
            INNER JOIN attractions a ON ai.id_attraction = a.id_attraction
            WHERE a.id_attraction = :id_attraction";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_attraction', $id_attraction, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTourData(PDO $pdo, $id_tour): array
{
    $sql = "SELECT a.attraction_name,a.address FROM tours t
    INNER JOIN tours_attractions ta ON t.id_tour = ta.id_tour
    INNER JOIN attractions a ON ta.id_attraction = a.id_attraction
    WHERE t.id_tour = :id_tour";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_tour', $id_tour, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCityNames(PDO $pdo, $id_tour): array
{
    $sql = "SELECT DISTINCT c.city_name FROM tours t
            INNER JOIN tours_attractions ta ON t.id_tour = ta.id_tour
            INNER JOIN attractions a ON ta.id_attraction = a.id_attraction
            INNER JOIN cities c ON a.id_city = c.id_city
            WHERE t.id_tour = :id_tour";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_tour', $id_tour, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deleteTableData(PDO $pdo, string $table_name, string $id_name, int $id): bool
{

    $sql = "DELETE FROM $table_name WHERE $id_name = :id;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    return $stmt->execute();
}

function getAllData(PDO $pdo, string $table_name): array
{
    $sql = "SELECT * FROM $table_name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCitiesWithAttractions(PDO $pdo): array
{
    $sql = "SELECT DISTINCT c.city_name,c.id_city
               FROM cities c 
               INNER JOIN attractions a ON c.id_city = a.id_city
               WHERE id_attraction > 0 ORDER BY c.city_name ASC ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insertNewTour(PDO $pdo, int $id_user, string $tour_name, string $tour_type): int
{
    $sql = "INSERT INTO tours (id_user,tour_name,tour_type) VALUES (:id_user,:tour_name,:tour_type)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_STR);
    $stmt->bindParam(':tour_name', $tour_name, PDO::PARAM_STR);
    $stmt->bindParam(':tour_type', $tour_type, PDO::PARAM_STR);
    $stmt->execute();

    return $pdo->lastInsertId();
}

function insertTourAttractions(PDO $pdo, int $id_tour, array $attractions): bool
{
    $count = 0;
    foreach ($attractions as $attraction) {
        $sql = "INSERT INTO tours_attractions (id_tour,id_attraction) VALUES (:id_tour,:id_attraction)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_tour', $id_tour, PDO::PARAM_STR);
        $stmt->bindParam(':id_attraction', $attraction, PDO::PARAM_STR);
        $stmt->execute();
        $count++;
    }
    if ($count< count($attractions))
        return "problem occured";
    else
        return "everything went fine";
}