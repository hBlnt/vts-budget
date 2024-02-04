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
            $data['firstname'] = $result['firstname'];
            $data['lastname'] = $result['lastname'];
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

function registerUser(PDO $pdo, string $password, string $firstname, string $lastname, string $email, string $token, int $news): int
{

    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users(password,firstname,lastname,email,news,registration_token, registration_token_expiry,active)
            VALUES (:passwordHashed,:firstname,:lastname,:email,:news,:token,DATE_ADD(now(),INTERVAL 1 DAY),0)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':passwordHashed', $passwordHashed, PDO::PARAM_STR);
    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':news', $news, PDO::PARAM_STR);
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

function getAttractionImagePath(PDO $pdo, string $id_attraction): string
{
    $sql = "SELECT i.path FROM 
            images i
            INNER JOIN attractions_images ai ON  i.id_image = ai.id_image
            INNER JOIN attractions a ON ai.id_attraction = a.id_attraction
            WHERE a.id_attraction = :id_attraction LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_attraction', $id_attraction, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_COLUMN);
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
    $sql = "SELECT a.attraction_name,a.address,a.id_attraction FROM tours t
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
    $sql = "SELECT a.attraction_name,a.id_attraction,c.city_name,a.id_city,fa.id_favourite FROM favourite_attractions fa
    INNER JOIN attractions a ON fa.id_attraction = a.id_attraction
    INNER JOIN cities c ON a.id_city = c.id_city
    WHERE fa.id_user = :id_user";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function deleteFavouriteAttraction(PDO $pdo, int $id_user, int $id_favourite, int $id_attraction): bool
{

    $sql = "DELETE FROM favourite_attractions WHERE id_favourite = :id_favourite AND id_user =:id_user;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_favourite', $id_favourite, PDO::PARAM_STR);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_STR);

    $sql2 = "UPDATE attractions SET popularity = popularity - 1 WHERE id_attraction = :id_attraction";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindParam(':id_attraction', $id_attraction, PDO::PARAM_STR);


    if ($stmt->execute() and $stmt2->execute())
        return true;
    else
        return false;
}

function updateFavouriteAttraction(PDO $pdo, int $id_user, int $id_favourite, int $id_attraction, int $new_favourite): bool
{

    $sql = "UPDATE favourite_attractions SET id_attraction = :new_favourite WHERE id_user = :id_user AND id_favourite = :id_favourite ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':new_favourite', $new_favourite, PDO::PARAM_STR);
    $stmt->bindParam(':id_favourite', $id_favourite, PDO::PARAM_STR);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_STR);

    $sql2 = "UPDATE attractions SET popularity = popularity - 1 WHERE id_attraction = :id_attraction";;
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindParam(':id_attraction', $id_attraction, PDO::PARAM_STR);

    $sql3 = "UPDATE attractions SET popularity = popularity + 1 WHERE id_attraction = :new_favourite;";
    $stmt3 = $pdo->prepare($sql3);
    $stmt3->bindParam(':new_favourite', $new_favourite, PDO::PARAM_STR);


    if ($stmt->execute() and $stmt2->execute() and $stmt3->execute())
        return true;
    else
        return false;
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
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function getAllData(PDO $pdo, string $table_name): array
{
    $sql = "SELECT * FROM $table_name";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

////////////////////////////////////////////////////////////////

/**
 * Function returns actual script name with extension
 * @return string
 */
function getCurrentPage(): string
{
    return substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
}

/**
 * @param int $number
 * @param mysqli $connection
 * @return int
 */

function insertIntoOrganizations(PDO $pdo, string $name, string $password, string $email, int $id_city): int
{

    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO organizations(org_name,password,email,id_city)
            VALUES (:org_name,:password,:email,:id_city)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':password', $passwordHashed, PDO::PARAM_STR);
    $stmt->bindParam(':org_name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':id_city', $id_city, PDO::PARAM_STR);
    $stmt->execute();

    return $pdo->lastInsertId();
}

function insertCity(PDO $pdo, string $name, string $country, string $image = ""): int
{
    $imageWithPath = '';
    if (!empty ($image))
        $imageWithPath = "db_images/city_images/" . $image;

    $sql = "INSERT INTO cities(country, city_name, city_image) 
    VALUES (:country , :city_name , :image )";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':country', $country, PDO::PARAM_STR);
    $stmt->bindParam(':city_name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':image', $imageWithPath, PDO::PARAM_STR);
    $stmt->execute();

    return $pdo->lastInsertId();
}

function dataExists(PDO $pdo, string $selectField, string $selectTable, array $whereFields, array $whereValues): bool
{
    $sql = "SELECT $selectField FROM $selectTable";

    $where = " WHERE $whereFields[0] = :value0";
    $params = [":value0" => $whereValues[0]];

    for ($i = 1; $i < count($whereFields); $i++) {
        $where .= " AND $whereFields[$i] = :value$i";
        $params[":value$i"] = $whereValues[$i];
    }

    $sql .= $where;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->rowCount() > 0 ? true : false;
}

/**
 * Checks if menu name exists in menus table
 * @param string $name
 * @param mysqli $connection
 * @return bool
 */
function getCountries(PDO $pdo): array
{

    $sql = 'SELECT DISTINCT c.country FROM cities c 
     INNER JOIN attractions a ON c.id_city = a.id_city
     WHERE a.id_city IN (SELECT id_city FROM cities)
     ORDER BY BINARY country ASC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

//nemAdmin

function getOrganization(PDO $pdo, int $id_organization): array
{

    $sql = "SELECT o.id_organization,o.org_name,o.email,o.is_banned,o.id_city,c.city_name FROM organizations o 
    INNER JOIN cities c ON o.id_city = c.id_city
    WHERE id_organization = '$id_organization'";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);

}

/**
 * Checks if any error happened during image upload, if file is uploaded with HTTP POST and if file type is JPG
 * @param array $image
 * @return bool
 */
function imageReady(array $image): bool
{
    //exif check
    return !(($image['error'] > 0 or !is_uploaded_file($image['tmp_name']) or exif_imagetype($image['tmp_name']) !== 2));
}


function is_ajax(): bool
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}


function updateOrganization(PDO $pdo, string $name, int $id_organization, string $email, int $id_city, int $status, string $password = ''): bool
{

    $sql = "UPDATE organizations SET org_name = :name, is_banned = :status, email = :email, id_city = :id_city ";

    if (!empty($password)) {
        $password2 = password_hash($password, PASSWORD_BCRYPT);
        $sql .= ", password = :password ";
    }
    $sql .= " WHERE id_organization = :id_organization";
    $stmt = $pdo->prepare($sql);
    if (!empty($password))
        $stmt->bindParam(':password', $password2, PDO::PARAM_STR);

    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':id_city', $id_city, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':id_organization', $id_organization, PDO::PARAM_STR);

    return $stmt->execute();

}

/**
 * @param string $str The string to sanitize
 * @return string Sanitized $str
 */
function sanitize(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function getUnusedCities($pdo): array
{

    $sql = 'SELECT c.id_city, c.city_name
                            FROM cities c
                            LEFT JOIN organizations o ON c.id_city = o.id_city
                            WHERE o.id_city IS NULL;
                            ';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function deleteCityImage(string $image): string
{
    if (file_exists($image)) {
        if (unlink($image)) {
            return "Image $image has been deleted successfully.";
        } else {
            return "Error deleting image $image.";
        }
    } else {
        return "Image $image does not exist.";
    }
}

function updateCity(PDO $pdo, string $name, int $id_city, string $country, string $image = ''): bool
{

    $sql = "UPDATE cities SET city_name = :name, country = :country";

    if (!empty($image)) {
        $sql .= ", city_image = :city_image ";
    }
    $sql .= " WHERE id_city = :id_city";
    $stmt = $pdo->prepare($sql);
    if (!empty($image))
        $stmt->bindParam(':city_image', $image, PDO::PARAM_STR);

    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':id_city', $id_city, PDO::PARAM_STR);
    $stmt->bindParam(':country', $country, PDO::PARAM_STR);

    return $stmt->execute();

}

function banUser(PDO $pdo, int $id_user): bool
{
    $sql = "UPDATE users SET is_banned = 1 WHERE id_user=:id_user";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_STR);
    return $stmt->execute();
}

function unbanUser(PDO $pdo, int $id_user): bool
{
    $sql = "UPDATE users SET is_banned = 0 WHERE id_user=:id_user";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_STR);
    return $stmt->execute();
}

/**
 * Gives back an array containing the comments data
 * @param string $comment
 * @return array
 */
function getFilteredCommentData(string $comment): array
{
    $badWords = $GLOBALS['badWords'];
    $comment = strtolower(trim($comment));
    $commentWords = str_word_count($comment, 1);
    $numberOfWords = str_word_count($comment);
    $newStr = implode(" ", $commentWords);

    foreach ($badWords as $word) {
        $newStr = str_replace($word, filterComment($word), $newStr);
    }

    $commentWordOccurence = array_count_values($commentWords);
    $words = [];
    $totalBadWords = 0;

    foreach ($badWords as $word) {
        if (array_key_exists($word, $commentWordOccurence)) {
            $words[$word] = $commentWordOccurence[$word];
            $totalBadWords += $commentWordOccurence[$word];
        }
    }

    $data = [
        'filteredComment' => $newStr,
        'words' => $words,
        'totalBadWords' => $totalBadWords,
        'totalWords' => $numberOfWords,
        'suggestedBadLevel' => suggestedBadLevel($totalBadWords, $numberOfWords)
    ];
    return $data;
}

/**
 * Inserts comment into comments and gives back lastInsertId
 * @param string $name
 * @param string $email
 * @param string $comment
 * @param array $commentData
 * @return int
 */
function insertComment(PDO $pdo, int $id_user, int $id_attraction, string $comment, array $commentData): int
{
    $sql = "INSERT INTO comments(id_user, id_attraction, comment,filtered_comment,total_bad_words,total_words,bad_level) 
            VALUES(:id_user, :id_attraction, :comment,:filtered_comment,:total_bad_words,:total_words,:bad_level)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_user', $id_user, PDO::PARAM_STR);
    $stmt->bindValue(':id_attraction', $id_attraction, PDO::PARAM_STR);
    $stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindValue(':filtered_comment', $commentData['filteredComment'], PDO::PARAM_STR);
    $stmt->bindValue(':total_bad_words', $commentData['totalBadWords'], PDO::PARAM_STR);
    $stmt->bindValue(':total_words', $commentData['totalWords'], PDO::PARAM_STR);
    $stmt->bindValue(':bad_level', $commentData['suggestedBadLevel'], PDO::PARAM_STR);

    $stmt->execute();
    return $pdo->lastInsertId();
}

/**
 * Censors comments
 * @param string $word
 * @return string
 */
function filterComment(string $word): string
{
    $pattern = $word[0];
    $length = strlen($word);
    $pattern .= str_repeat("*", $length - 2);
    return $pattern . $word[$length - 1];
}

/**
 * Returns the badness level of the comment
 * @param int $totalBadWords
 * @param int $totalWords
 * @return int
 */
function suggestedBadLevel(int $totalBadWords, int $totalWords): int
{
    if ($totalBadWords === $totalWords)
        $data = 7;
    elseif ($totalBadWords > $totalWords * (2 / 100) && $totalBadWords < $totalWords * (19 / 100))
        $data = 2;
    elseif ($totalBadWords >= $totalWords * (20 / 100) && $totalBadWords < $totalWords * (39 / 100))
        $data = 3;
    elseif ($totalBadWords >= $totalWords * (40 / 100) && $totalBadWords < $totalWords * (59 / 100))
        $data = 4;
    elseif ($totalBadWords >= $totalWords * (60 / 100) && $totalBadWords < $totalWords * (79 / 100))
        $data = 5;
    elseif ($totalBadWords >= $totalWords * (80 / 100))
        $data = 6;
    else $data = 0;


    return $data;
}

/**
 * Inserts bad words into bad_words table, if there are any
 * @param PDO $pdo
 * @param int $id_comment
 * @param string $word
 * @param int $number
 * @return void
 */
function insertIntoBadWords(PDO $pdo, int $id_comment, string $word, int $number): void
{
    $sql = "INSERT INTO bad_words(id_comment,word,number) 
            VALUES(:id_comment, :word, :number);";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_comment', $id_comment, PDO::PARAM_STR);
    $stmt->bindValue(':word', $word, PDO::PARAM_STR);
    $stmt->bindValue(':number', $number, PDO::PARAM_STR);

    $stmt->execute();
}

function getCommentData(PDO $pdo, int $id_attraction): array
{
    $sql = " SELECT c.filtered_comment,c.date_time,u.firstname,c.bad_level FROM comments c 
    INNER JOIN attractions a ON c.id_attraction = a.id_attraction
    INNER JOIN users u ON c.id_user = u.id_user 
    WHERE a.id_attraction = :id_attraction
 ORDER BY c.date_time DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_attraction', $id_attraction, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function checkAdminLogin(PDO $pdo, string $username, string $enteredPassword): array
{
    $sql = "SELECT id_admin, password,username FROM admins WHERE username=:username";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);

    $data = [];

    $stmt->execute();


    if ($stmt->rowCount() > 0)
        $result = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($stmt->rowCount() > 0) {

        $registeredPassword = $result['password'];

        if (password_verify($enteredPassword, $registeredPassword)) {
            $data['id_admin'] = $result['id_admin'];
            $data['username'] = $result['username'];
        }
    }

    return $data;
}

function isTroll(PDO $pdo, int $id_user): bool
{
    $sql = "SELECT CASE WHEN COUNT(*) > 0 THEN 'true' ELSE 'false' END AS result
FROM comments
WHERE id_user = :id_user  AND bad_level >= :bad_level;";

    $bad_level = 4;
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_STR);
    $stmt->bindParam(':bad_level', $bad_level, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->fetchColumn() == 'true')
        return true;
    else
        return false;
}

function getDateBackwards(int $timeStamp): string
{
    $currentTime = new DateTime();
    $commentTime = DateTime::createFromFormat('U', $timeStamp);
    $interval = $currentTime->diff($commentTime);

    if ($interval->y > 0) {
        $backwards = $interval->y . " year" . ($interval->y > 1 ? "s" : "") . " ago";
    } elseif ($interval->m > 0) {
        $backwards = $interval->m . " month" . ($interval->m > 1 ? "s" : "") . " ago";
    } elseif ($interval->d > 0) {
        $backwards = $interval->d . " day" . ($interval->d > 1 ? "s" : "") . " ago";
    } elseif ($interval->h > 0) {
        $backwards = $interval->h . " hour" . ($interval->h > 1 ? "s" : "") . " ago";
    } elseif ($interval->i > 0) {
        $backwards = $interval->i . " minute" . ($interval->i > 1 ? "s" : "") . " ago";
    } else {
        $backwards = "moments ago";
    }

    return $backwards;

}

function updateAttraction(PDO $pdo, int $id_attraction, string $attraction_name, string $description, string $address, string $type): bool
{
    $sql = "UPDATE attractions SET attraction_name = :attraction_name, description = :description, address = :address, type = :type
   WHERE id_attraction = :id_attraction";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_attraction', $id_attraction, PDO::PARAM_STR);
    $stmt->bindParam(':attraction_name', $attraction_name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);

    return $stmt->execute();
}

function getAllAttractionTypes(PDO $pdo): array
{

    $sql = "SHOW COLUMNS FROM attractions LIKE 'type'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $types = $stmt->fetch(PDO::FETCH_ASSOC);
    $enum_values = explode("','", substr($types['Type'], 6, -2));
    sort($enum_values);
    return $enum_values;
}

function getAllAttractionDataByID(PDO $pdo, int $id_attraction): array
{

//admin


    $sql = "SELECT a.attraction_name,a.type,a.address,a.description,a.id_organization,a.id_city,o.org_name,c.city_name FROM attractions a
    INNER JOIN cities c ON a.id_city = c.id_city
    INNER JOIN organizations o ON a.id_organization = o.id_organization
    WHERE a.id_attraction = :id_attraction";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_attraction', $id_attraction, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateAttractionAllFields(PDO $pdo, int $id_attraction, int $id_organization, int $id_city, string $attraction_name, string $type, string $address, string $description): bool
{

//admin

    $sql = "UPDATE attractions SET attraction_name = :attraction_name, description = :description, address = :address, type = :type, id_organization = :id_organization, id_city = :id_city
   WHERE id_attraction = :id_attraction";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':attraction_name', $attraction_name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);
    $stmt->bindParam(':id_attraction', $id_attraction, PDO::PARAM_STR);
    $stmt->bindParam(':id_city', $id_city, PDO::PARAM_STR);
    $stmt->bindParam(':id_organization', $id_organization, PDO::PARAM_STR);

    return $stmt->execute();
}

function getGoodComments(PDO $pdo, int $id_attraction): array
{
    $sql = " SELECT c.filtered_comment,c.date_time,u.firstname,c.bad_level FROM comments c 
    INNER JOIN attractions a ON c.id_attraction = a.id_attraction
    INNER JOIN users u ON c.id_user = u.id_user 
    WHERE a.id_attraction = :id_attraction AND c.bad_level < :okay_bad_level
 ORDER BY c.date_time DESC
    ";

    $okay_bad_level = 6;
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_attraction', $id_attraction, PDO::PARAM_STR);
    $stmt->bindParam(':okay_bad_level', $okay_bad_level, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllCountries(PDO $pdo): array
{

//admin

    $sql = "SHOW COLUMNS FROM cities LIKE 'country'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $types = $stmt->fetch(PDO::FETCH_ASSOC);
    $enum_values = explode("','", substr($types['Type'], 6, -2));
    sort($enum_values);
    return $enum_values;
}

function getAttractionsByCity(PDO $pdo, int $id_city): array
{
    $sql = "SELECT c.id_city,a.id_attraction,a.attraction_name FROM cities c
    INNER JOIN attractions a ON c.id_city = a.id_city
    WHERE c.id_city = :id_city ORDER BY a.attraction_name ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_city', $id_city, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

function isSameCity(PDO $pdo, int $new_favourite, int $id_favourite): bool
{
    $sql1 = "SELECT id_city FROM attractions WHERE id_attraction = :new_favourite";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->bindParam(':new_favourite', $new_favourite, PDO::PARAM_STR);
    $stmt1->execute();
    $new_favourite_city = $stmt1->fetchColumn();
    if (!$new_favourite_city)
        return false;

    $sql2 = "SELECT f.id_attraction FROM favourite_attractions f INNER JOIN attractions a ON f.id_attraction = a.id_attraction WHERE a.id_city = :new_favourite_city AND f.id_favourite = :id_favourite";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindParam(':new_favourite_city', $new_favourite_city, PDO::PARAM_STR);
    $stmt2->bindParam(':id_favourite', $id_favourite, PDO::PARAM_STR);
    $stmt2->execute();

    if ($stmt2->rowCount() > 0) {
        return true;
    }
    return false;
}

/**Function detects ip address of the request.
 * It returns valid ip address or unknown word.
 * @return string
 */
function getIpAddress(): string
{

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        $ip = "unknown";
    }

    return $ip;
}

/**Function inserts data into log table.
 * @param string $userAgent
 * @param string $ipAddress
 * @param string $deviceType
 * @param string $country
 * @param bool $proxy
 * @return void
 */
function insertIntoLog(PDO $pdo, int $id_user, string $userAgent, string $ipAddress, string $deviceType, string $country, bool $proxy): void
{

    $sql = "INSERT INTO user_informations(user_agent, ip_address,device_type, country, proxy, id_user ) VALUES(:userAgent, :ipAddress,:deviceType, :country, :proxy, :id_user)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':userAgent', $userAgent, PDO::PARAM_STR);
    $stmt->bindValue(':ipAddress', $ipAddress, PDO::PARAM_STR);
    $stmt->bindValue(':country', $country, PDO::PARAM_STR);
    $stmt->bindValue(':proxy', $proxy, PDO::PARAM_INT);
    $stmt->bindValue(':deviceType', $deviceType, PDO::PARAM_STR);
    $stmt->bindValue(':id_user', $id_user, PDO::PARAM_STR);

    $stmt->execute();
}

/**Function executes curl session and returns the transfer as a string of the return value of execution.
 * @param $url
 * @return string
 */
function getCurlData($url): string
{
    // https://www.php.net/manual/en/book.curl.php

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}
