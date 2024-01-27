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
    $sqlOrg = "SELECT id_organization, id_city, password FROM organizations WHERE email=:email AND is_banned = 0 LIMIT 0,1";

    $stmtUser = $pdo->prepare($sqlUser);
    $stmtUser->bindParam(':email', $email, PDO::PARAM_STR);

    $stmtOrg = $pdo->prepare($sqlOrg);
    $stmtOrg->bindParam(':email', $email, PDO::PARAM_STR);
    $data = [];

    $stmtUser->execute();
    $stmtOrg->execute();


    if ($stmtUser->rowCount() > 0) {
        $result = $stmtUser->fetch(PDO::FETCH_ASSOC);
    } else if ($stmtOrg->rowCount() > 0) {

        $result = $stmtOrg->fetch(PDO::FETCH_ASSOC);
    }

    if ($stmtUser->rowCount() > 0) {

        $registeredPassword = $result['password'];

        if (password_verify($enteredPassword, $registeredPassword)) {
            $data['id_user'] = $result['id_user'];
        }
    } else if ($stmtOrg->rowCount() > 0) {
        $registeredPassword = $result['password'];
        if (password_verify($enteredPassword, $registeredPassword)) {
            $data['id_organization'] = $result['id_organization'];
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

const GMailUSEREmail = 'personaltrainerhelp2023@gmail.com'; // your username on gmail
const GoogleAppsPassword = 'ybyvuesekrtnjjbb'; // you password for created APP

function sendEmail(PDO $pdo, string $email, array $emailData, string $body, int $id_user): void
{

    $toEmail = $email;
    $subject = $emailData['subject'];
    $from = 'personaltrainerhelp2023@gmail.com';
    $fromName = 'Tourist website';


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

function getAttractionImagePath(PDO $pdo, string $id_attraction): array|bool
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
    if ($count < count($attractions))
        return "problem occured";
    else
        return "everything went fine";
}

function isFavouriteAttractionExist(PDO $pdo, int $id_city, int $id_user): bool
{
    $sql = "
            SELECT 
              IF(COUNT(*) > 0, 'true', 'false') AS result_exists
            FROM 
              favourite_attractions fa
              INNER JOIN attractions a ON fa.id_attraction = a.id_attraction
              INNER JOIN cities c ON a.id_city = c.id_city
            WHERE 
              id_user = :id_user AND c.id_city = :id_city;
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_STR);
    $stmt->bindParam(':id_city', $id_city, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $result = $stmt->fetchColumn();
        return $result === 'true';
    }

    return false;  // Return false if execute fails

}

function getFavouriteAttractions(PDO $pdo, int $id_user): array
{
    $sql = "SELECT a.attraction_name,a.id_attraction,c.city_name,a.id_city FROM favourite_attractions fa
    INNER JOIN attractions a ON fa.id_attraction = a.id_attraction
    INNER JOIN cities c ON a.id_city = c.id_city
    WHERE fa.id_user = :id_user";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deleteFavouriteAttraction(PDO $pdo, int $id_user, int $id_attraction): bool
{

    $sql = "DELETE FROM favourite_attractions WHERE id_attraction = :id_attraction AND id_user =:id_user;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_attraction', $id_attraction, PDO::PARAM_STR);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_STR);

    $sql2 = "UPDATE attractions SET popularity = popularity - 1 WHERE id_attraction = :id_attraction";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindParam(':id_attraction', $id_attraction, PDO::PARAM_STR);

    $stmt->execute();
    $stmt2->execute();
    return $pdo->lastInsertId();
}

function insertFavouriteAttraction(PDO $pdo, int $id_user, int $id_attraction): int
{
    $sql = "INSERT INTO favourite_attractions (id_user,id_attraction) VALUES 
    (:id_user,:id_attraction)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_attraction', $id_attraction, PDO::PARAM_STR);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_STR);

    $sql2 = "UPDATE attractions SET popularity = popularity + 1 WHERE id_attraction = :id_attraction";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindParam(':id_attraction', $id_attraction, PDO::PARAM_STR);

    $stmt->execute();
    $stmt2->execute();
    return $pdo->lastInsertId();
}

function existsAttraction(PDO $pdo, string $attraction_name): bool
{

    $sql = "SELECT id_attraction FROM attractions WHERE attraction_name=:attraction_name";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':attraction_name', $attraction_name, PDO::PARAM_STR);
    $stmt->execute();
    $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

function getCityIdByOrganization(PDO $pdo, int $id_organization): int
{
    $sql = "SELECT id_city FROM organizations WHERE id_organization = :id_organization";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_organization', $id_organization, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn();

}

function insertAttraction(PDO $pdo, string $attraction_name, string $type, string $description, string $address, int $id_organization): int
{
    $sql = "INSERT INTO attractions (attraction_name,type,description,address,id_organization,id_city) VALUES 
    (:attraction_name,:type,:description,:address,:id_organization,:id_city)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':attraction_name', $attraction_name, PDO::PARAM_STR);
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':id_organization', $id_organization, PDO::PARAM_STR);
    $id_city = getCityIdByOrganization($pdo, $id_organization);
    $stmt->bindParam(':id_city', $id_city, PDO::PARAM_STR);

    $stmt->execute();
    return $pdo->lastInsertId();
}

function insertImage(PDO $pdo, string $path): int
{
    $sql = "INSERT INTO images (path) VALUES (:path)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':path', $path, PDO::PARAM_STR);

    $stmt->execute();
    return $pdo->lastInsertId();
}

function insertAttractionImage(PDO $pdo, int $id_attraction, int $id_image): int
{
    $sql = "INSERT INTO attractions_images (id_attraction,id_image) VALUES (:id_attraction,:id_image)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_attraction', $id_attraction, PDO::PARAM_STR);
    $stmt->bindParam(':id_image', $id_image, PDO::PARAM_STR);

    $stmt->execute();
    return $pdo->lastInsertId();
}

function deleteImages(PDO $pdo, int $id_attraction): bool
{
    $sql = "DELETE i
FROM images i
INNER JOIN attractions_images ai ON
    i.id_image = ai.id_image
WHERE
    ai.id_attraction = :id_attraction";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_attraction', $id_attraction, PDO::PARAM_STR);
    return $stmt->execute();

}

function getImageNames(PDO $pdo, int $id_attraction): array
{
    $sql = "SELECT i.path FROM images i 
            INNER JOIN attractions_images ai 
            ON i.id_image = ai.id_image
            WHERE ai.id_attraction = :id_attraction";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_attraction', $id_attraction, PDO::PARAM_STR);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}