<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "codeoner";

$mysqli = new mysqli($servername, $username, $password, $dbname);
// Verificar la conexión
if ($mysqli->connect_error) {
    echo json_encode(["success" => false, "message" => "Conexión fallida: " . $mysqli->connect_error]);
    exit();
}
?>