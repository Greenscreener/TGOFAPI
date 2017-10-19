<?php
include "secrets.php";
require __DIR__ . '/vendor/autoload.php';
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

$signer = new Sha256();

$username = $_POST["username"];
$password = hash("sha256", $_POST["password"].$passsalt);

$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
$username = $conn->real_escape_string($username);
$password = $conn->real_escape_string($password);

$sql = 'SELECT * FROM `TGOFAPI Users` WHERE `Username` ="' . $username . '" AND `PassHash` ="' . $password . '"';

if ($conn->connect_error) {
    http_response_code(500);
    header("Content-Type: application/json");
    die("{\n    \"error\":\"" . $conn->connect_error . "\"\n}");
}
$result = $conn->query($sql);
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $token = (new Builder())->setIssuedAt(time())
                            ->setExpiration(time() + 3600)
                            ->set('Username', $row["Username"])
                            ->set('ID', $row["ID"])
                            ->set('ValidEmail', $row["ValidEmail"])
                            ->sign($signer, $signature)
                            ->getToken();
    header("Content-Type: application/json");
    http_response_code(200);
    echo "{\n    \"token\":\"" . $token . "\"\n}";
} else {
    http_response_code(401);
    header("Content-Type: application/json");
    echo "{\n    \"error\":\"Invalid Credentials\"}";
}


?>
