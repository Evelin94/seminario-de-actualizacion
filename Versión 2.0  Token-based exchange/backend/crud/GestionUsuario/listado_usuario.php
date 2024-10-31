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
    $autenticacion = validateSession($husuario,$hpassword);
 
}


if ($autenticacion) {
    try {
        // Establecer la conexión usando PDO
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // Establecer el modo de error PDO a excepción
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ejecutar la consulta
        $stmt = $conn->prepare("SELECT * FROM usuarios");
        $stmt->execute();

        // Obtener los resultados
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Responder con los datos y el nuevo token si es método token
        $response = [
            'data' => $result,
        ];

        if ($_SESSION['authMethod'] == 'token') {
            $response['token'] = $newtoken;
        } 

        echo json_encode($response);
    } catch (PDOException $e) {
        // Devolver mensaje de error
        echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
        exit;
    } finally {
        // Cerrar la conexión
        $conn = null;
    }
} else {
    echo json_encode(['error' => 'Token de sesión no válido']);
}
?>