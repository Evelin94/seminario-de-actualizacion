<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "iniciosesion";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(array("message" => "Conexión fallida: " . $e->getMessage())));
}
