<?php
session_start();
include('../conn.php');
include('../verificarSesion.php');

if (validateSessionToken($htoken)) {
try {
    // Crear una nueva conexión PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparar y ejecutar la consulta SQL
    $stmt = $conn->prepare("SELECT idUsuarios, usuario FROM usuarios");
    $stmt->execute();

    // Obtener todos los resultados como un array asociativo
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Establecer el encabezado de contenido como JSON y devolver los resultados
    header('Content-Type: application/json');
    echo json_encode($usuarios);
} catch (PDOException $e) {
    // Manejo de errores
    header('Content-Type: application/json');
    echo json_encode(["error" => "Conexión fallida: " . $e->getMessage()]);
}

// Cerrar la conexión (opcional en PDO, pero bueno para claridad)
$conn = null;
} else {
    echo json_encode(['error' => 'Token de sesión no válido']);
}