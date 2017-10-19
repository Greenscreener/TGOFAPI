<?php
include "secrets.php";

if (!$_POST["username"]) {
    http_response_code(400);
    header("Content-Type: application/json");
    die("{\n    \"error\":\"Username missing.\"}");
}
$username = $_POST["username"];
if ($_POST["password"] != $_POST["passwordConfirm"]) {
    http_response_code(400);
    header("Content-Type: application/json");
    die("{\n    \"error\":\"Passwords don't match.\"}");
}
if (strlen($_POST["password"]) < 8) {
    http_response_code(400);
    header("Content-Type: application/json");
    die("{\n    \"error\":\"Password too short. (Min. 8 chars.)\"}");
}
$password = hash("sha256", $_POST["password"].$passsalt);
if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    header("Content-Type: application/json");
    die("{\n    \"error\":\"Invalid email.\"}");
}
$email = $_POST["email"];
$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
$username = $conn->real_escape_string($username);
$password = $conn->real_escape_string($password);

$sql = 'SELECT * FROM `TGOFAPI Users` WHERE `Username` ="' . $username . '"';

if ($conn->connect_error) {
    http_response_code(500);
    header("Content-Type: application/json");
    die("{\n    \"error\":\"" . $conn->connect_error . "\"\n}");
}

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    http_response_code(400);
    header("Content-Type: application/json");
    die("{\n    \"error\":\"User exists.\"}");
}
$id = uniqid();
$sql = "INSERT INTO `TGOFAPI Users` (`Username`, `PassHash`, `Email`, `ValidEmail`, `ID`) values ('". $username ."','". $password ."','". $email ."', 0,'". $id ."')";
email($email, "PLS VALIDATE", "clik mi: <a href='//tgofapi.grnscrnr.tk/validate.php?id=" . $id . "'>PLS</a>)
