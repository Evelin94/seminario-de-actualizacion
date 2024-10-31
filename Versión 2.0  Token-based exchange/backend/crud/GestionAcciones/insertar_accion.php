<?php
session_start();
include('../conn.php');

// VARIABLE DE AUTENTICACIÓN
$autenticacion = false;

if ($_SESSION['authMethod'] == 'token') {
    include('../verificarSesion.php');
    include('../generarNewToken.php');
    $autenticacion = validateSessionToken($htoken);
    $newtoken = generateSessionToken();
    $_SESSION['session_token'] = $newtoken;
}

if ($_SESSION['authMethod'] == 'userPass') {
    include('../verificarSesionUser.php');
    $autenticacion = validateSession($husuario, $hpassword);
}

if ($autenticacion) {

try {
    // Crear conexión usando PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configurar el modo de error de PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar el método de la solicitud
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(array('success' => false, 'message' => 'Método no permitido'));
        http_response_code(405); // Método no permitido
        exit();
    }

    // Obtener datos del cuerpo de la solicitud JSON
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['accion'])) {
        echo json_encode(array('success' => false, 'message' => 'Datos incompletos'));
        http_response_code(400); // Bad request
        exit();
    }

    $accion = $data['accion'];
    
    // Preparar y ejecutar la inserción
    $stmt = $conn->prepare("INSERT INTO acciones (Accion) VALUES (:accion)");
    $stmt->bindParam(':accion', $accion);

        if ($stmt->execute()) {

            if ($_SESSION['authMethod'] == 'token') {
                echo json_encode(array('success' => true, 'token' => $newtoken, 'message' => 'Accion registrada exitosamente'));
            }

            if ($_SESSION['authMethod'] == 'userPass') {
                echo json_encode(array('success' => true, 'message' => 'Accion registrada exitosamente'));
            }
            
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error al registrar accion'));
    }
} catch (PDOException $e) {
    echo json_encode(array('success' => false, 'message' => 'Error: ' . $e->getMessage()));
}

// Cerrar conexión
$conn = null;
} else {
    echo json_encode(['error' => 'Token de sesión no válido']);
}
