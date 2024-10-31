<?php

// INSTANCIA DE CONEXION
$conn = new mysqli($servername, $username, $password, $dbname);

// VERIFICA CONEXION
if ($conn->connect_error) {
die("Conexión fallida: " . $conn->connect_error);
}