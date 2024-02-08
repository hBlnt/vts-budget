<?php
require_once 'config.php';
$GLOBALS['pdo'] = connectDatabase($dsn, $pdoOptions);


function connectDatabase(string $dsn, array $pdoOptions): PDO
{

    try {
        $pdo = new PDO($dsn, PARAMS['USER'], PARAMS['PASSWORD'], $pdoOptions);
    } catch (\PDOException $e) {
        var_dump($e->getCode());
        throw new \PDOException($e->getMessage());
    }

    return $pdo;
}

function checkUserLogin(string $email, string $enteredPassword): array|false
{
    $sqlUser = "SELECT id_user, password FROM users WHERE email=:email AND active=1 AND is_banned = 0 LIMIT 0,1";

    $stmtUser = $GLOBALS['pdo']->prepare($sqlUser);
    $stmtUser->bindParam(':email', $email, PDO::PARAM_STR);

    $loginData = [];

    $stmtUser->execute();


    if ($stmtUser->rowCount() > 0) {
        $result = $stmtUser->fetch(PDO::FETCH_ASSOC);
    } else {
        return false;
    }

    if ($stmtUser->rowCount() > 0) {

        $registeredPassword = $result['password'];

        if (password_verify($enteredPassword, $registeredPassword)) {
            $loginData['id_user'] = $result['id_user'];
        }
    }

    return $loginData;
}

function getBearerToken(): string
{
    $token = "";
    $headers = apache_request_headers();

    if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
        $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];
    } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $authorizationHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    } elseif (isset($headers['Authorization'])) {
        $authorizationHeader = $headers['Authorization'];
    } else {
        $authorizationHeader = '';
    }

    if (!empty($authorizationHeader)) {
        $parts = explode(' ', $authorizationHeader);
        if (count($parts) === 2 && strtolower($parts[0]) === 'bearer') {
            $token = $parts[1];
        }
    }
    return $token;
}

function checkToken(string $token): array
{
    $sql = "SELECT id_token FROM token WHERE value=:value AND expires > now()";
    $stmt = $GLOBALS['pdo']->prepare($sql);
    $stmt->bindValue("value", $token, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

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


function getAttractionByID(int $id_attraction): array|bool
{
    $sql = "SELECT a.id_attraction,a.attraction_name,a.type,a.description,c.city_name,o.org_name,a.id_city FROM attractions a 
    INNER JOIN organizations o ON a.id_organization = o.id_organization
    INNER JOIN cities c ON a.id_city = c.id_city
    WHERE id_attraction = :id_attraction
    ";

    $stmt = $GLOBALS['pdo']->prepare($sql);
    $stmt->bindValue(':id_attraction', $id_attraction, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAttractions(array $fields): array|bool
{
    $fieldsDb = implode(',', $fields);
    $sql = "SELECT $fieldsDb FROM attractions ORDER BY attraction_name";
    $stmt = $GLOBALS['pdo']->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getFavouriteAttractions(int $id_user): array|bool
{
    $sql = "SELECT a.attraction_name,a.id_attraction,c.city_name,a.id_city,fa.id_favourite FROM favourite_attractions fa
    INNER JOIN attractions a ON fa.id_attraction = a.id_attraction
    INNER JOIN cities c ON a.id_city = c.id_city
    WHERE fa.id_user = :id_user";
    $stmt = $GLOBALS['pdo']->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserData(int $id_user): array|bool
{
    $sql = "SELECT firstname,lastname FROM users WHERE id_user=:id_user";

    $stmt = $GLOBALS['pdo']->prepare($sql);
    $stmt->bindValue(':id_user', $id_user, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function deleteFavouriteAttraction(int $id_favourite): bool
{

    $sql = "DELETE FROM favourite_attractions WHERE id_favourite = :id_favourite;";
    $stmt = $GLOBALS['pdo']->prepare($sql);
    $stmt->bindParam(':id_favourite', $id_favourite, PDO::PARAM_STR);


    if ($stmt->execute())
        return true;
    else
        return false;
}

function isFavouriteAttractionExist(int $id_city, int $id_user): bool
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

    $stmt = $GLOBALS['pdo']->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_STR);
    $stmt->bindParam(':id_city', $id_city, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $result = $stmt->fetchColumn();
        return $result === 'true';
    }

    return false;  // Return false if execute fails

}

function insertFavouriteAttraction(int $id_user, int $id_attraction): int|bool
{
    $sql = "INSERT INTO favourite_attractions (id_user,id_attraction) VALUES 
    (:id_user,:id_attraction)";
    $stmt = $GLOBALS['pdo']->prepare($sql);
    $stmt->bindParam(':id_attraction', $id_attraction, PDO::PARAM_STR);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_STR);


    $stmt->execute();
    return $GLOBALS['pdo']->lastInsertId();
}
